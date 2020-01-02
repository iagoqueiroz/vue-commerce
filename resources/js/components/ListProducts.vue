<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="font-weight-bold">Product List</span>
                    <input v-model="search" @keyup="searchFetch" type="text" class="form-control col-3 form-control-sm" placeholder="Search for name...">
                    <div class="buttons d-flex">
                        <import-file></import-file>
                        <router-link :to="{name: 'product.create'}" class="btn btn-sm btn-success ml-3">Create Product</router-link>
                    </div>
                </div>
                <div class="card-body">
                    <div class="loading text-center" v-if="isLoading">
                        <i class="fas fa-sync-alt fa-spin mr-1"></i> Loading...
                    </div>
                    <table class="table table-bordered" v-else>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th class="text-center" width="140px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(product, index) in products.data" :key="index">
                                <td class="align-middle">{{ product.name }}</td>
                                <td class="align-middle">{{ product.price | toCurrency }}</td>
                                <td class="align-middle">{{ product.category.name }}</td>
                                <td class="text-center d-flex justify-content-around align-items-center">
                                    <router-link class="btn btn-sm btn-outline-success" :to="{name: 'product.view', params: {id: product.id}}"><i class="fas fa-eye" alt="View" title="View"></i></router-link>
                                    <router-link class="btn btn-sm btn-outline-info" :to="{name: 'product.edit', params: {id: product.id}}"><i class="fas fa-edit" alt="Edit" title="Edit"></i></router-link>
                                    <a href="" class="btn btn-sm btn-outline-danger" @click.prevent="deleteProduct(product.id, index)"><i class="fas fa-trash-alt" alt="Delete" title="Delete"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer" v-if="!isLoading">
                    <pagination :data="products" ref="pagination" size="small" align="center" @pagination-change-page="fetchData"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
const pagination = require('laravel-vue-pagination');
import _ from 'lodash';
import ImportFile from './ImportProduct';

export default {
    data() {
        return {
            isLoading: true,
            products: {},
            search: '',
        }
    },
    methods: {
        fetchData(page = 1) {
            this.products = {};
            this.isLoading = true;
            axios.get(`products/list?page=${page}&search=${this.search}`)
                .then(res => {
                    this.products = res.data;
                    this.isLoading = false;
                }).catch(error => {
                    console.log(error);
                });
        },
        searchFetch: _.debounce(function() {
            this.fetchData();
        }, 500),
        deleteProduct(id, index) {
            if(confirm('You really want to delete this product ?')) {
                axios({
                    url: `/products/delete/${id}`,
                    method: 'delete'
                }).then(res => {
                    this.products.data.splice(index, 1);
                }).catch(err => {
                    console.log(err)
                })
            }
        }
    },
    created() {
        this.fetchData();
    },
    filters: {
        toCurrency(value) {
            return new Intl.NumberFormat('en-US', {style: 'currency', currency: 'USD'}).format(value);
        }
    },
    components: {
        pagination,
        ImportFile
    }
}
</script>

<style>
    ul.pagination {
        margin: 0;
    }
</style>