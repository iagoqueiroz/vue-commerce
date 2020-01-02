<template>
    <div class="row justify-content-md-center">
        <div class="col-8">
            <div class="card" v-if="isLoaded">
                <div class="card-header">
                    Edit Product #{{ product.id }}
                </div>
                <div class="card-body">
                    <div class="alert alert-success" v-if="success">
                        Product edited !
                    </div>
                    <form @submit.prevent="formSubmit" class="form" id="edit-form">
                        <div class="form-group position-relative upload-file">
                            <label for="upload-file">
                                <i class="fas fa-upload"></i> UPLOAD
                            </label>
                            <input type="file" id="upload-file" accept="image/*" ref="files" @change="handleFile">
                            <div class="invalid-feedback" :class="{'d-inline-block': errors.file}" v-if="error && errors.file">
                                {{ errors.file[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" :class="{'is-invalid': error && errors.name}" id="name" v-model="product.name">
                            <div class="invalid-feedback" v-if="error && errors.name">
                                {{ errors.name[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" :class="{'is-invalid': error && errors.price}" id="price" v-model="product.price">
                            <div class="invalid-feedback" v-if="error && errors.price">
                                {{ errors.price[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="category" class="form-label">Category</label>
                            <select v-model="product.category_id" id="category" class="form-control" :class="{'is-invalid': error && errors.category_id}">
                                <option :value="category.id" v-for="category in categories" :key="category.id">{{ category.name }}</option>
                            </select>
                            <div class="invalid-feedback" v-if="error && errors.category_id">
                                {{ errors.category_id[0] }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea v-model="product.description" id="description" cols="30" rows="10" class="form-control" :class="{'is-invalid': error && errors.description}"></textarea>
                            <div class="invalid-feedback" v-if="error && errors.description">
                                {{ errors.description[0] }}
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <button @click="submitForm" class="btn btn-success btn float-right px-4">Edit</button>
                    <router-link :to="{name: 'product.list'}" class="btn btn-link float-right px-4">Back</router-link>
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
            product: {},
            categories: [],
            error: null,
            success: null,
            errors: []
        }
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            this.isLoaded = false;
            axios({
                url: `/products/edit/${this.$route.params.id}`,
                method: 'get'
            }).then(res => {
                this.isLoaded = true;
                this.product = res.data.data.product;
                this.categories = res.data.data.categories;
            });
        },
        submitForm() {
            let formData = new FormData();
            formData.append('name', this.product.name);
            formData.append('category_id', this.product.category_id);
            formData.append('description', this.product.description);
            formData.append('price', this.product.price);
            if(this.$refs.files.files.length){
                formData.append('file', this.$refs.files.files[0]);
            }
            formData.append('_method', 'PUT');

            axios({
                url: `/products/edit/${this.$route.params.id}`,
                method: 'post',
                data: formData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(res => {
                this.success = true;
                this.error = false;

                setTimeout(function() {
                    this.$route.push('/products');
                }, 2000);
            }).catch(err => {
                this.success = false;
                this.error = true;
                this.errors = err.response.data.errors;
            })
        },
        handleFile() {
            let file = this.$refs.files.files[0];
            let reader = new FileReader();

            reader.onload = (e) => {
                let img = document.createElement('img');
                img.src = e.target.result;

                let uploadLabel = document.querySelector('.upload-file label');
                uploadLabel.innerHTML = '';
                uploadLabel.appendChild(img);
            }

            reader.readAsDataURL(file);
        }
    },
}
</script>

<style>
     #edit-form .form-label {
        font-weight: bold;
        text-transform: uppercase;
        font-size: 12px;
    }

    .upload-file input[type="file"] {
        display: none;
    }

    .upload-file label {
        display: block;
        text-align: center;
        background: #F2F2F2;
        border: 2px dashed #C2C2C2;
        border-radius: 3px;
        padding: 30px;
        font-weight: bold;
        text-transform: uppercase;
        cursor: pointer;
        overflow: hidden;
    }

    .upload-file label img {
        display: block;
        width: 100%;
        height: auto;
    }
</style>