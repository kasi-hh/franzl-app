<template>
    <q-page padding>
        <q-card>
            <q-card-section>
                <q-img :src="image.href"></q-img>
            </q-card-section>
            <q-card-section>
                <q-input v-model="image.title" label="Titel" placeholder="Titel"></q-input>
                <q-option-group inline v-model="image.tag" :options="tagOptions" @input="tagChanged"></q-option-group>
                <textarea style="border:1px solid #999; width:100%; margin-top:10px;height:100px" v-model="image.info" placeholder="Textbild"></textarea>
                <q-input v-model="image.latitude" label="Latitude" placeholder="Latitude"></q-input>
                <q-input v-model="image.longitude" label="Longitude" placeholder="Longitude"></q-input>
            </q-card-section>
            <q-card-actions>
                <q-btn glossy unelevated color="grey-10" class="full-width" dark icon="send" @click="submit">Submit</q-btn>
            </q-card-actions>
            <q-card-section>
            </q-card-section>
        </q-card>
    </q-page>
</template>

<script>
    import {Loading, QSpinnerGears} from 'quasar'

    export default {
        name: 'PageName',
        props: {
            image: Object
        },
        data() {
            return {
                tagOptions: [
                    {label: 'Franzl', value: 'franzl'},
                    {label: 'Urlaub', value: 'urlaub'}
                ]
            }
        },
        methods: {
            submit() {
                Loading.show();
                try {
                    this.$axios.post('/api/image/save', this.image).then((response) => {
                        Loading.hide();
                        this.$root.scrollTop = 0;
                        this.$router.push('/')
                    }).catch((error) => {
                        Loading.hide()
                        this.$q.notify({
                            message: error.toString()
                        })
                    })
                } catch (err) {
                    Loading.hide();
                    this.$q.notify({
                        message: 'err:' + JSON.stringify(err.toString())
                    })
                }
            },
            tagChanged(value) {
                this.$q.localStorage.set('tag', value)
            }
        }
    }
</script>

<style>
</style>
