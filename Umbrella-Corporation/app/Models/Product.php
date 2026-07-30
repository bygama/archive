<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSecurityLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasSecurityLevel;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'type',
        'id_code',
        'price',
        'stock',
        'status',
        'status_key',
        'clearance',
        'clearance_key',
        'facility',
        'risk_index',
        'containment_class',
        'format',
        'color_visual',
        'image',
        'icon',
        'description',
        'body',
        'dossier',
        'last_revision',
    ];

    protected $casts = [
        'price' => 'integer',
        'dossier' => 'array',
        'last_revision' => 'date',
    ];

    /**
     * el trait HasSecurityLevel usa esta columna como llave.
     * en productos el nivel va en clearance_key y el status_key es el estado operativo.
     */
    protected string $securityKey = 'clearance_key';

    /**
     * la categoria a la que pertenece el producto
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * filtra el catalogo por categoria y/o por clearance. los nulos los ignora
     *
     * @param  Builder      $query
     * @param  string|null  $categorySlug
     * @param  string|null  $clearanceKey
     * @return Builder
     */
    public function scopeFiltered(Builder $query, ?string $categorySlug, ?string $clearanceKey): Builder
    {
        return $query
            ->when($categorySlug, fn (Builder $q, string $slug) => $q->whereHas(
                'category',
                fn (Builder $sub) => $sub->where('slug', $slug)
            ))
            ->when($clearanceKey, fn (Builder $q, string $key) => $q->where('clearance_key', $key));
    }

    /**
     * pasa el risk_index tipo "96/100" a porcentaje
     *
     * @return int
     */
    public function getRiskPercentageAttribute(): int
    {
        if (preg_match('/(\d+)\s*\/\s*(\d+)/', (string) $this->risk_index, $m) === 1) {
            return (int) round(((int) $m[1] / max(1, (int) $m[2])) * 100);
        }

        return 0;
    }

    /**
     * el slug de la categoria, y si no hay lo armo con el nombre
     *
     * @return string
     */
    public function getCategorySlugAttribute(): string
    {
        return $this->category?->slug ?? Str::slug((string) $this->category?->name);
    }

    /**
     * las suscripciones contratadas sobre este producto
     *
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * las lineas de pedido que incluyen este producto
     *
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
