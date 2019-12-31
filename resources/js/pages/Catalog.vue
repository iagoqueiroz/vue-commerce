<template>
    <div>
        <div class="row row-cols-1 row-cols-md-3">
            <div class="col mb-4" v-for="(product, index) in products" :key="index">
                <div class="card h-100 position-relative">
                    <div class="top-absolute d-flex justify-content-between">
                        <div class="favorite">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="price">{{ product.price | toCurrency }}</div>
                    </div>
                    <img :src="'/storage/uploads/images/' + product.image_path" class="card-img-top" alt="">
                    <div class="card-body">
                        <div class="top-section d-flex justify-content-between">
                            <div class="category">{{ product.category.name }}</div>
                            <div class="stars">
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <h5 class="card-title">{{ product.name }}</h5>
                        <p class="card-text">{{ product.description }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="loading d-block text-center" v-if="isLoading"><i class="fas fa-sync-alt fa-spin"></i></div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            products: {},
            page: 1,
            limit: 1,
            isLoading: false,
        }
    },
    methods: {
        scroll() {
            window.onscroll = () => {
                let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight;

                if(bottomOfWindow && this.page !== this.limit) {
                    this.page += 1;
                    this.isLoading = true;
                    axios({
                        url: `/products/catalog?page=${this.page}`,
                        method: 'get'
                    }).then(res => {
                        this.products = this.products.concat(res.data.data);
                        this.isLoading = false;
                    }).catch(err => {
                        console.log(err);
                    });
                }
            }
        },
        fetchInitial() {
            axios({
                url: `/products/catalog?page=${this.page}`,
                method: 'get'
            }).then(res => {
                this.products = res.data.data;
                this.limit = res.data.meta.last_page
            }).catch(err => {
                console.log(err);
            });
        }
    },
    filters: {
        toCurrency(value) {
            return new Intl.NumberFormat('en-US', {style: 'currency', currency: 'USD'}).format(value);
        }
    },
    mounted() {
        this.scroll()
    },
    beforeMount() {
        this.fetchInitial();
    }
}
</script>

<style scoped>
    .card img {
        width: 100%;
        max-height: 250px;
        height: 250px;
        object-fit: cover;
        filter: brightness(50%);
        transition: all .2s ease-in-out;
    }

    .card {
        transition: all .2s ease-in-out;
        border: none;
    }

    .card:hover {
        box-shadow: 0 0 14px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }

    .card:hover img {
        filter: brightness(1);
    }

    .card .top-section {
        padding: 7px 0;
    }

    .card .top-section .category {
        font-family: "Raleway", sans-serif;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: bold;
        cursor: pointer;
    }

    .card .top-section .stars i {
        color:goldenrod;
        font-size: 12px;
        cursor: pointer;
    }

    .card .top-section .stars i:hover {
        color:red;
    }

    .card .card-title {
        font-weight: bold;
        color: #333;
    }

    .card .card-text {
        line-height: 1.5rem;
        color: #666;
    }

    .card .top-absolute {
        position: absolute;
        display: block;
        top: 0;
        z-index: 999;
        width: 100%;
    }

    .card .price {
        padding: 4px 12px;
        background-color: #2ecc71;
        color: #fff;
        font-weight: bold;
        border-radius: 20px;
        margin: 15px;
    }

    .card .favorite {
        padding: 4px;
        width: 31px;
        height: 31px;
        position: relative;
        color: #fff;
        border-radius: 50%;
        background-color: transparent;
        margin: 15px;
        border: 2px solid #fff;
        transition: all .2s ease;
        cursor: pointer;
    }

    .card .favorite:hover {
        background-color: #e74c3c;
    }

    .card .favorite i {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>