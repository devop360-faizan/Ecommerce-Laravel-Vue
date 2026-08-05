import { defineStore } from 'pinia';

interface CartItem {
  id: number;
  name: string;
  price: number;
  quantity: number;
  image_url: string;
}

export const useCartStore = defineStore('cart', {
  state: () => ({
    // Hum shuru mein check karenge ke localStorage mein koi purana cart hai ya nahi
    items: JSON.parse(localStorage.getItem('cart') || '[]') as CartItem[],
  }),

  getters: {
    totalItems: (state) => state.items.reduce((total, item) => total + item.quantity, 0),
    totalPrice: (state) => state.items.reduce((total, item) => total + (item.price * item.quantity), 0),
  },

  actions: {
    addToCart(product: any, quantity: number = 1) {
      // Check karte hain agar product pehle se cart mein hai
      const existingItem = this.items.find(item => item.id === product.id);
      
      if (existingItem) {
        existingItem.quantity += quantity;
      } else {
        // Agar naya product hai toh usay items array mein daal do
        this.items.push({
          id: product.id,
          name: product.name,
          price: product.price,
          quantity: quantity,
          image_url: product.images && product.images.length > 0 ? product.images[0].url : 'https://via.placeholder.com/150'
        });
      }
      this.saveCart();
    },

    removeFromCart(productId: number) {
      this.items = this.items.filter(item => item.id !== productId);
      this.saveCart();
    },

    clearCart() {
      this.items = [];
      this.saveCart();
    },

    saveCart() {
      // Cart ko browser mein save kar lo taake refresh karne par delete na ho
      localStorage.setItem('cart', JSON.stringify(this.items));
    }
  }
});
