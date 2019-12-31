<template>
    <div id="main-wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
            <div class="container">
                <router-link :to="{name: 'home'}" class="navbar-brand d-flex align-items-center">
                    <img src="https://images.opencollective.com/vuejs/25a8146/logo.png" width="80px" height="auto" alt="">
                    <span class="text-uppercase">VUE-Commerce</span>
                </router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <router-link :to="{name: 'home'}" class="nav-link">Home</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{name: 'catalog'}" class="nav-link">Catalog</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{name: 'product.list'}" v-if="$auth.user().admin" class="nav-link">Products</router-link>
                        </li>
                        <li class="nav-item" v-if="!$auth.check()">
                            <router-link :to="{name: 'login'}" class="ml-3 btn btn-success">Login</router-link>
                        </li>
                        <li class="nav-item" v-else>
                            <a href="" @click.prevent="$auth.logout()" class="ml-3 btn btn-danger">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container my-3">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }

</script>
