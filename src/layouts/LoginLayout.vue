<template>
  <q-layout view="lHh Lpr lFf" class="bg-grey-10">
    <q-header elevated>
      <q-toolbar>
        <q-toolbar-title class="text-center">
          Franzl App
        </q-toolbar-title>
      </q-toolbar>
    </q-header>
    <q-page-container>
      <q-page class="row flex-center">
        <q-card style="width:90vw;max-width:600px">
          <q-card>
            <q-card-section>
              <q-input :rules="validation" clearable filled v-model="username" label="Username" placeholder="Username"></q-input>
              <q-input type="password" :rules="validation" clearable filled v-model="password" label="Passwort" placeholder="Passwort"></q-input>
            </q-card-section>
            <q-card-actions>
              <q-btn glossy unelevated color="grey-10" class="full-width" dark icon="send" @click="login">Login</q-btn>
            </q-card-actions>
          </q-card>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
  import axios from "../boot/axios";
  import {LocalStorage} from "quasar";

  export default {
    name: 'LoginLayout',
    data() {
      return {
        username: '',
        password: '',
        validation: [(val, name) => !!val || `Username und Paswort sind erforderlich ${name}`]
      }
    },
    methods: {
      login() {
        document.cookie = 'XDEBUG_SESSION=PHPSTORM;path=/;;';
        //document.cookie = 'XDEBUG_SESSION=;expires=Mon, 05 Jul 2000 00:00:00 GMT;path=/;';
        this.$axios.post('/api/auth/login', {username: this.username, password: this.password}).then((response) => {
          if (response.data.success === true) {
            this.$q.localStorage.set('token', response.data.token)
            this.$axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.token;
            this.$router.replace('/')
          } else {
            this.loginFailed(response)
          }
        }).catch((error) => {
          this.loginFailed(response)
        })
      },
      loginFailed(response) {
        this.$q.notify({
          message: 'Login fehlgeschlagen'
        })
      },
      test() {
        const url = 'testing';
        this.$router.push({name: 'post', params: {image: {href: url, id: '', title: '', info: '', created: ''}}})
      },
    }
  }
</script>

<style>
</style>
