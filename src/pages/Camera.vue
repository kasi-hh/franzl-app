<template>
    <q-page padding>
        <q-card>
            <q-card-section>
                <video style="width:100%" ref="video" @click="onVideoClicked" autoplay></video>
            </q-card-section>
        </q-card>
    </q-page>
</template>

<script>
    export default {
        name: 'Camera',
        data(){
            return  {
            }
        },
        mounted() {
            this.clearStream();
            const constraints = {
                video: {
                    deviceId: {exact: this.$q.localStorage.getItem('deviceId')},
                    width: {exact: 1024},
                    height: {exact: 768}
                }
            };

            navigator.mediaDevices.getUserMedia(constraints).then((stream) => {
                this.stream = stream; // make stream available to console
                this.$refs.video.srcObject = stream;
            }).catch((error) => {
                console.log('getUserMediaError', error)
            });
        },
        destroyed(){
            this.clearStream();
        },
        methods: {
            clearStream() {
                if (this.stream) {
                    this.stream.getTracks().forEach((track) => {
                        track.stop();
                    });
                }
            },
            onVideoClicked() {
                const canvas = document.createElement('canvas');
                const videoElement = this.$refs.video;
                if (this.stream) {
                    canvas.width = videoElement.videoWidth;
                    canvas.height = videoElement.videoHeight;
                    canvas.getContext('2d').drawImage(videoElement, 0, 0);
                    // Other browsers will fall back to ima+ge/png
                    const url = canvas.toDataURL('image/jpg');
                    const tag = this.$q.localStorage.has('tag') ? this.$q.localStorage.getItem('tag') : '';

                    navigator.geolocation.getCurrentPosition((res)=>{
                        const latitude = res.coords.latitude;
                        const longitude = res.coords.longitude;
                        this.$router.push({
                            name:'post',
                            params:{image:{href:url,id:'',title:'',info:'',created:'',tag, latitude, longitude}}})
                    },()=>{
                        this.$router.push({
                            name:'post',
                            params:{image:{href:url,id:'',title:'',info:'',created:'',tag,latitude:'',longitude:''}}})
                    })
                }
            }
        }

    }
</script>

<style>
</style>
