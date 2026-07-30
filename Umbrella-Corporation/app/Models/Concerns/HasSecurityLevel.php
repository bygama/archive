<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * comparte el "nivel de seguridad" entre Product y Post: la etiqueta en español,
 * el color para pintar en el front y un scope para filtrar. cada modelo dice en
 * que columna guarda el key con $securityKeyColumn.
 */
trait HasSecurityLevel
{
    /** @var array<string, array{label: string, tone: string}> */
    private static array $securityMap = [
        'critical'   => ['label' => 'CRÍTICO',     'tone' => 'red'],
        'restricted' => ['label' => 'RESTRINGIDO', 'tone' => 'crimson'],
        'classified' => ['label' => 'CLASIFICADO', 'tone' => 'void'],
        'nominal'    => ['label' => 'NOMINAL',     'tone' => 'green'],
        'standby'    => ['label' => 'EN ESPERA',   'tone' => 'grey'],
        'clear'      => ['label' => 'LIBRE',       'tone' => 'white'],
    ];

    /**
     * la etiqueta en español del nivel, ej "CRÍTICO"
     *
     * @return string
     */
    public function getSecurityLabelAttribute(): string
    {
        return self::$securityMap[$this->{$this->securityKeyColumn()}]['label']
            ?? strtoupper((string) $this->{$this->securityKeyColumn()});
    }

    /**
     * el color que le toca al nivel para el front
     *
     * @return string
     */
    public function getSecurityToneAttribute(): string
    {
        return self::$securityMap[$this->{$this->securityKeyColumn()}]['tone'] ?? 'grey';
    }

    /**
     * filtra por nivel de seguridad. si no le pasas key devuelve todo igual
     *
     * @param  Builder      $query
     * @param  string|null  $key
     * @return Builder
     */
    public function scopeBySecurity(Builder $query, ?string $key): Builder
    {
        if ($key === null || $key === '') {
            return $query;
        }

        return $query->where($this->securityKeyColumn(), $key);
    }

    protected function securityKeyColumn(): string
    {
        return property_exists($this, 'securityKey') ? $this->securityKey : 'security_key';
    }
}
