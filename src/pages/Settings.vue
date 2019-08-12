<template>
    <q-page padding>
        <q-card>
            <q-card-section>
                <q-select v-model="deviceId" :options="options" label="Kamera" @input="setDeviceId"></q-select>
            </q-card-section>
        </q-card>
    </q-page>
</template>

<script>
    import {LocalStorage} from 'quasar'

    export default {
        name: 'PageName',
        data() {
            return {
                deviceId: '',
                options: []
            }
        },
        mounted() {
            if (this.$q.localStorage.has('deviceId')){
                this.deviceId = this.$q.localStorage.getItem('deviceId')
            }
            let options = [];
            navigator.mediaDevices.enumerateDevices()
                .then((deviceInfos) => {

                    for (const deviceInfo of deviceInfos) {
                        if (deviceInfo.kind === 'videoinput' || navigator.userAgent.match(/iphone/i)) {
                            options.push({
                                label: deviceInfo.label || 'camera ' + (options.length + 1),
                                value: deviceInfo.deviceId
                            })
                        } else {
                            console.log('Found one other kind of source/device: ', deviceInfo);
                        }
                    }
                    if (!this.deviceId && options.length > 0){
                        this.deviceId = options[0];
                        this.setDeviceId();
                    }
                    this.options = options;
                    /*
                    if (navigator.userAgent.match(/Win64/)) {
                        let tmpImg = new Image();
                        let image = this.$refs.image;
                        let canvas = this.$refs.canvas;
                        tmpImg.onload = () => {
                            canvas.width = tmpImg.naturalWidth;
                            canvas.height = tmpImg.naturalHeight;
                            canvas.getContext('2d').drawImage(tmpImg, 0, 0);
                            // Other browsers will fall back to ima+ge/png
                            image.src = canvas.toDataURL('image/jpg');
                        }
                        tmpImg.src = '/images/test.jpg';
                    }
                     */
                }).catch((error) => {
                console.log('enumerateDeviceError', error)
            });
        },
        methods:{
            setDeviceId(){
                console.log('deviceId', this.deviceId)
                this.$q.localStorage.set('deviceId', this.deviceId.value);
            }
        }
    }
</script>

<style>
</style>
