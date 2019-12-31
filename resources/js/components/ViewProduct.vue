<template>
    <div class="row justify-content-md-center">
        <div class="col-8">
            <div class="card" v-if="isLoaded">
                <img :src="'/storage/uploads/images/' + product.image_path" alt="" width="100%" height="auto">
                <div class="card-body">
                    <h4 class="card-title">{{ product.name }}</h4>
                    <p class="card-text description">{{ product.description }}</p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <div class="category">
                        <b>Category:</b> <span>{{ product.category.name }}</span>
                    </div>
                    <div class="price">
                        <b>Price:</b> <span class="text-success">{{ product.price | toCurrency }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoaded: false,
            product: null
        }
    },
    created() {
        this.fetch();
    },
    methods: {
        fetch() {
            this.isLoaded = false;
            axios({
                url: `products/${this.$route.params.id}`,
                method: 'get'
            }).then(res => {
                this.product = res.data.data;
                this.isLoaded = true;
            }).catch(error => {
                console.log(error);
            });
        }
    },
    filters: {
        toCurrency(value) {
            return new Intl.NumberFormat('en-US', {style: 'currency', currency: 'USD'}).format(value);
        }
    }
}
</script>

<style>
    .description {
        font-family: "Raleway", sans-serif;
        line-height: 1.6em;
    }
</style>