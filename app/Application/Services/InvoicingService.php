<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Finance\Models\Invoice;
use App\Domain\Finance\Models\InvoiceLine;
use App\Domain\Finance\Models\Payment;
use App\Domain\Reservation\Models\Reservation;
use App\Domain\Shared\Enums\InvoiceStatus;
use App\Domain\Shared\Enums\PaymentStatus;
use Carbon\Carbon;

class InvoicingService
{
    public function generateInvoiceFromReservation(Reservation $reservation): Invoice
    {
        $primaryGuest = $reservation->guests()
            ->wherePivot('is_primary', true)
            ->first();

        $invoice = Invoice::create([
            'number' => $this->generateInvoiceNumber(),
            'reservation_id' => $reservation->id,
            'guest_id' => $primaryGuest?->id,
            'status' => InvoiceStatus::DRAFT,
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => 0,
            'taxes' => 0,
            'total' => 0,
        ]);

        $subtotal = 0;
        $taxes = 0;

        foreach ($reservation->reservationItems as $item) {
            $checkIn = Carbon::parse($item->date_from);
            $checkOut = Carbon::parse($item->date_to);
            $nights = $checkIn->diffInDays($checkOut);

            $lineSubtotal = $item->price_per_night * $nights;
            $lineTaxes = $item->taxes_per_night * $nights;
            $lineTotal = $lineSubtotal + $lineTaxes;

            $taxRate = $lineTaxes > 0 ? ($lineTaxes / $lineSubtotal) * 100 : 0;

            InvoiceLine::create([
                'invoice_id' => $invoice->id,
                'description' => sprintf(
                    '%s - %s to %s (%d night%s)',
                    $item->roomType->name,
                    $checkIn->format('M d, Y'),
                    $checkOut->format('M d, Y'),
                    $nights,
                    $nights !== 1 ? 's' : ''
                ),
                'quantity' => $nights,
                'unit_price' => $item->price_per_night,
                'subtotal' => $lineSubtotal,
                'tax_rate' => $taxRate,
                'tax_amount' => $lineTaxes,
                'total' => $lineTotal,
            ]);

            $subtotal += $lineSubtotal;
            $taxes += $lineTaxes;
        }

        $invoice->update([
            'subtotal' => $subtotal,
            'taxes' => $taxes,
            'total' => $subtotal + $taxes,
        ]);

        return $invoice->fresh(['invoiceLines']);
    }

    public function recordPayment(Invoice $invoice, array $paymentData): Payment
    {
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'method' => $paymentData['method'],
            'status' => $paymentData['status'] ?? PaymentStatus::PENDING,
            'amount' => $paymentData['amount'],
            'paid_at' => $paymentData['paid_at'] ?? null,
            'reference' => $paymentData['reference'] ?? null,
        ]);

        $totalPaid = $invoice->payments()
            ->where('status', PaymentStatus::PAID)
            ->sum('amount');

        if ($totalPaid >= $invoice->total) {
            $invoice->status = InvoiceStatus::PAID;
            $invoice->save();
        }

        return $payment;
    }

    protected function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $lastInvoice = Invoice::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        $sequence = $lastInvoice ? ((int) substr($lastInvoice->number, -6)) + 1 : 1;

        return sprintf('INV-%s-%06d', $year, $sequence);
    }
}
