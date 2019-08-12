<template>
    <q-layout view="lHh Lpr lFf">
        <q-header elevated>
            <q-toolbar>
                <q-btn to="/"><q-icon name="home"></q-icon></q-btn>
                <q-btn to="/settings"><q-icon name="settings"></q-icon></q-btn>
                <q-toolbar-title class="text-center">
                    Franzl
                </q-toolbar-title>
                <q-btn to="/camera"><q-icon name="camera"></q-icon></q-btn>
            </q-toolbar>
        </q-header>

        <q-page-container class="bg-grey-10">
            <router-view></router-view>
        </q-page-container>
    </q-layout>
</template>

<script>

    export default {
        name: 'AppLayout',
        data() {
            return {
            }
        },
        methods: {
        }
    }
</script>

<style>
</style>
