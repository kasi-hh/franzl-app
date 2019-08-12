<template>
    <q-page padding>
        <q-card @click="$router.go(-1)">
            <q-card-section>
                <div class="headline>">{{image.title}} vom {{image.created}}</div>
            </q-card-section>
            <q-card-section>
                <q-img :src="image.href"></q-img>
            </q-card-section>
            <q-card-section>
                <div>{{image.info}}</div>
            </q-card-section>
        </q-card>
        <q-card>
            <bing-maps style="height:500px" :center="center" :title="image.title" :info="image.info"></bing-maps>
        </q-card>
    </q-page>
</template>

<script>
    import Vue from 'vue'
    import BingMaps from '../components/BingMaps'
//'AIzaSyDynzyO-sBrdSlnPtZkmlxRlvQw60OEN6I',
    export default {
        components:{BingMaps},
        name: 'Show',
        props: {
            image: Object
        },
        data() {
            return {
                zoom:5
            }
        },
        computed:{
            center() {
                return {latitude: this.image.latitude, longitude:this.image.longitude}
            }
        }
    }
</script>

<style>
</style>
