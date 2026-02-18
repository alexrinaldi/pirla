<?php

declare(strict_types=1);

namespace App\Domain\Finance\Models;

use App\Domain\Shared\Enums\InvoiceStatus;
use App\Domain\Shared\Traits\BelongsToHotel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use BelongsToHotel, HasFactory;

    protected $fillable = [
        'hotel_id',
        'reservation_id',
        'guest_id',
        'invoice_number',
        'status',
        'subtotal',
        'tax_amount',
        'service_charge',
        'discount',
        'total',
        'notes',
        'issued_at',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'issued_at' => 'datetime',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the reservation for this invoice.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Reservation\Models\Reservation::class);
    }

    /**
     * Get the guest for this invoice.
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Guest\Models\Guest::class);
    }

    /**
     * Get the invoice lines for this invoice.
     */
    public function invoiceLines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class);
    }

    /**
     * Get the payments for this invoice.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the balance remaining on this invoice.
     */
    public function getBalanceAttribute(): float
    {
        return (float) ($this->total - $this->payments->sum('amount'));
    }
}
