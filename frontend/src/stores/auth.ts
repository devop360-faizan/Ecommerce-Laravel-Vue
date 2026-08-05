import { defineStore } from "pinia";
import api from "../services/api";

interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  role: string;
}

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user:null as User | null,
        token: localStorage.getItem('token') || null,
    }), 
    getters: {
        isAuthenticated: (state) => !!state.token,
        isAdmin: (state) => state.user?.role === 'admin',
    },
    actions: {
        async login(credentials: any){
            try{
                const response = await api.post('/auth/login', credentials);

                const { access_token, user } = response.data.data;

                this.token = access_token;
                this.user = user;

                localStorage.setItem('token', access_token);

                return true;
            }catch(error){
                throw error;
            }
        },

        async fetchUser() {
            if (!this.token) return;
            try {
                const response = await api.get('/auth/me');
                this.user = response.data.data;
            } catch (error) {
                this.logout(); 
            }
        },
        logout() {
            this.user = null;
            this.token = null;
            localStorage.removeItem('token');
        }
  }
});
