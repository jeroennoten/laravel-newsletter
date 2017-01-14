<template>
    <div>
        <div v-if="!adding">
            <p>Zorg dat het Excel-bestand aan de volgende eisen voldoet:</p>
            <ul>
                <li>De eerste kolom bevat e-mailadressen.</li>
                <li>De tweede kolom bevat namen (optioneel).</li>
                <li>De eerste rij zijn koppen (en wordt dus genegeerd).</li>
            </ul>
            <form enctype="multipart/form-data" v-el:form @submit.prevent="submit">
                <div class="form-group">
                    <label for="fileInputfield">Bestand</label>
                    <input name="file" type="file" id="fileInputfield" v-model="file">
                </div>
                <button type="submit" class="btn btn-success" :disabled="uploading">
                    <i class="fa fa-spinner fa-pulse" v-show="uploading"></i>
                    <i class="fa fa-upload" v-else></i>
                    Upload
                </button>
            </form>
        </div>
        <div v-else>
            <p v-if="canAddMore">Klaar! <button class="btn btn-xs btn-success" @click="addMore">Meer leden toevoegen</button></p>
            <p v-else><strong>Toevoegen van addressen...</strong></p>
            <add-members :addresses="addresses" v-else></add-members>
        </div>
    </div>
</template>

<script>
    import submitAddresses from '../submitAddresses';

    export default {
        props: ['listId'],
        data() {
            return {
                uploading: false,
                adding: false,
                addresses: [],
                canAddMore: false,
                file: ''
            }
        },
        methods: {
            submit() {
                var data = new FormData(this.$els.form);
                this.uploading = true;
                this.$http.post('/admin/newsletter-lists/parse', data).then(response => {
                    this.uploading = false;
                    this.adding = true;
                    this.addresses = response.data.map(row => ({
                        address: row[0],
                        name: row[1],
                        status: 'pending',
                    }));
                    return submitAddresses(this.listId, this.addresses);
                }).then(() => {
                    this.canAddMore = true;
                });
            },
            addMore() {
                this.file = '';
                this.adding = false;
            }
        }
    }
</script>