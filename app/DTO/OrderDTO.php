<?php

declare(strict_types=1);

namespace App\DTO;

class OrderDTO
{
    public function __construct(private string $type, private array $products,  private ?int $rentHour)
    {
    }

    /**
     * @return int
     */
    public function getRentHour(): int
    {
        return $this->rentHour;
    }

    /**
     * @param int $rentHour
     */
    public function setRentHour(int $rentHour): void
    {
        $this->rentHour = $rentHour;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param array $products
     */
    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'products' => $this->getProducts(),
            'rent_hour' => $this->getRentHour()
        ];
    }
}
