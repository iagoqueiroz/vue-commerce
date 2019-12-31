<template>
    <div>
        <input type="file" style="display: none;" accept=".csv" ref="inputFile" @change="handleFile">
        <button class="btn btn-primary btn-sm" @click="triggerInput" ref="buttonFile"><i class="fas fa-upload"></i> Import</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            file: '',
        }
    },
    methods: {
        triggerInput() {
            this.$refs.inputFile.click();
        },
        handleFile() {
            const file = this.$refs.inputFile;

            let formData = new FormData();
            formData.append('imported_file', file.files[0]);

            const button = this.$refs.buttonFile;
            button.innerHTML = `<i class="fas fa-sync-alt fa-spin px-3"></i>`;

            axios({
                url: '/products/import',
                method: 'post',
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                data: formData
            }).then(res => {
                button.innerHTML = `<i class="fas fa-check-circle px-3"></i>`;

                setTimeout(() => {
                    button.innerHTML = `<i class="fas fa-upload"></i> Import`;
                    file.value = '';
                }, 1500);
            }).catch(err => {
                button.innerHTML = `<i class="fas fa-times-circle px-3"></i>`;

                setTimeout(() => {
                    button.innerHTML = `<i class="fas fa-upload"></i> Import`;
                    file.value = '';
                }, 1500);

                console.log(err.response.data.errors);
            })
        }
    }
}
</script>

<style>

</style>