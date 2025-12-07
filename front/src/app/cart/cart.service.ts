import { Injectable, signal, computed } from '@angular/core';
import { Product } from '../products/data-access/product.model';

export interface CartItem {
    product: Product;
    quantity: number;
}

@Injectable({
    providedIn: 'root'
})

export class CartService {

    private readonly _items = signal<CartItem[]>([]);
    public readonly items = this._items.asReadonly();

    public readonly totalQuantity = computed(() =>
        this._items().reduce((sum, item) => sum + item.quantity, 0)
    );

    public readonly totalPrice = computed(() =>
        this._items().reduce((sum, item) => sum + item.quantity * item.product.price, 0)
    );

    constructor() { }

    add(product: Product, qty: number = 1) {
        this._items.update(items => {
            const index = items.findIndex(i => i.product.id === product.id);
            // Product in a cart
            if (index > -1) {
                items[index] = {
                    ...items[index],
                    quantity: items[index].quantity + qty
                };
                return [...items];
            }

            // Product not in cart
            return [
                ...items,
                {
                    product,
                    quantity: qty
                }
            ];
        });
    }

    remove(productId: number) {
        this._items.update(items => items.filter(i => i.product.id !== productId));
    }

    clear() {
        this._items.set([]);
    }
}
