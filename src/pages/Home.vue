<template>
    <q-page padding>
        <q-toolbar>
            <q-option-group dark inline v-model="currentTag" :options="tagOptions" @input="tagChanged"></q-option-group>
        </q-toolbar>
        <div class="row">
            <q-card class="col-md-4 col-6" v-for="(image,index) in filteredImages" :key="image.id">
                <q-card-section>
                    <q-img @click="click(images[index], image.id)" :src="image.href"></q-img>
                </q-card-section>
                <q-card-section>{{image.title}} vom {{image.created}}
                <q-icon name="edit" @click="edit(images[index], image.id)"></q-icon>
                <q-icon style="margin-left: 50px" name="delete" @click="deleteImage(images[index], index)"></q-icon>
                </q-card-section>
            </q-card>
        </div>
    </q-page>
</template>

<script>
    import { Loading,  QSpinnerGears } from 'quasar'

    export default {
        name: 'Home',
        data() {
            return {
                tagOptions:[
                    { label: 'Franzl', value: 'franzl' },
                    { label: 'Urlaub', value: 'urlaub' },
                    { label: 'Alle', value: 'alle' },
                ],
                currentTag:'alle',
                images: []
            }
        },
        created() {
            this.currentTag = this.$q.localStorage.has('currentTag') ? this.$q.localStorage.getItem('currentTag') : 'alle';
            this.$axios.get('/api/image/list').then(response => {
                if (response.data.success){
                    this.images = response.data.data;
                    this.$q.localStorage.set('images', response.data.data)
                    this.$nextTick(()=>{
                        if (this.$root.scrollTop){
                            setTimeout(()=>{
                                document.documentElement.scrollTo(0,this.$root.scrollTop)
                            },2000)
                        }
                    })
                }
                else {
                    if (this.$q.localStorage.has('images')){
                        this.images = this.$q.localStorage.getItem('images')
                    }
                }
            }).catch((error) => {
                if (this.$q.localStorage.has('images')){
                    this.images = this.$q.localStorage.getItem('images')
                }
            })
        },
        computed:{
            filteredImages() {
                console.log('filtered', this.currentTag)
                if (this.currentTag === 'alle'){
                    return this.images;
                }
                else {
                    return this.images.filter((image)=>image.tag === this.currentTag)
                }
            }
        },
        methods:{
            click(image, id) {
                this.$root.scrollTop = document.documentElement.scrollTop;
                if (image.id !== id){
                    image = this.images.find((img)=>img.id===id)
                }
                this.$router.push({name:'show',params:{image}})
            },
            edit(image, id) {
                this.$root.scrollTop = document.documentElement.scrollTop;
                if (image.id !== id){
                    image = this.images.find((img)=>img.id===id)
                }
                this.$router.push({name:'post',params:{image}})
            },
            deleteImage(image, index){
                this.$q.dialog({
                    title: 'Bild löschen',
                    message: `Soll das Bild #${image.id} ${image.title} vom ${image.created} sicher gelöscht werden?`,
                    cancel: true,
                    persistent: true
                }).onOk(() => {
                    Loading.show();
                    this.$axios.post('/api/image/delete', image).then(response=>{
                        Loading.hide();
                        if (response.data.success === true){
                            this.images.splice(index,1)
                        }
                    }).catch(()=>{
                        Loading.hide();
                    })
                })
            },
            tagChanged(value) {
                this.$q.localStorage.set('currentTag', value);
            }
        }
    }
</script>

<style>
    .q-radio--dark .q-radio__label {
        color:#fff
    }
</style>
