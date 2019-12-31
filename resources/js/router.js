import VueRouter from 'vue-router';

import Home from './pages/Home';
import Catalog from './pages/Catalog';
import Login from './pages/Login';
import Register from './pages/Register';
import ListProducts from './components/ListProducts';
import CreateProduct from './components/CreateProduct';
import EditProduct from './components/EditProduct';
import ViewProduct from './components/ViewProduct';

import Error403 from './error/403';

// Routes
const routes = [{
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            auth: undefined
        }
    },
    {
        path: '/catalog',
        name: 'catalog',
        component: Catalog,
        meta: {
            auth: undefined
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
    {
        path: '/products',
        name: 'product.list',
        component: ListProducts,
        meta: {
            auth: {
                roles: 1
            }
        }
    },
    {
        path: '/products/edit/:id',
        name: 'product.edit',
        component: EditProduct,
        meta: {
            auth: {
                roles: 1
            }
        }
    },
    {
        path: '/products/view/:id',
        name: 'product.view',
        component: ViewProduct,
        meta: {
            auth: {
                roles: 1
            }
        }
    },
    {
        path: '/products/create',
        name: 'product.create',
        component: CreateProduct,
        meta: {
            auth: {
                roles: 1
            }
        }
    },
    {
        path: '/403',
        name: 'error403',
        component: Error403
    }

];

const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
});

export default router
