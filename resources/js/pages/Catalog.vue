<template>
    <div>
        <div class="row row-cols-1 row-cols-md-3">
            <div class="col mb-4" v-for="(product, index) in products" :key="index">
                <div class="card h-100">
                    <img :src="'/storage/uploads/images/' + product.image_path" class="card-img-top" alt="">
                    <div class="card-body">
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
        transition: all .2s ease-in-out
    }

    .card:hover {
        box-shadow: 0 0 6px rgba(0, 0, 0, 0.2);
        transform: translateY(-5px);
    }

    .card:hover img {
        filter: brightness(1);
    }
</style>