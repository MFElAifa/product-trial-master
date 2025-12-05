<?php
namespace App\Enum;

enum ProductStatus: string
{
    case INSTOCK = 'INSTOCK';
    case LOWSTOCK = 'LOWSTOCK';
    case OUTOFSTOCK = 'OUTOFSTOCK';
}