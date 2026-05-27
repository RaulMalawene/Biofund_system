<template>
  <router-view v-slot="{ Component, route }">
    <keep-alive :key="authStore.sessionId" :max="12">
      <component v-if="route.meta.keepAlive" :is="Component" :key="route.path" />
    </keep-alive>
    <component v-if="!route.meta.keepAlive" :is="Component" :key="route.path" />
  </router-view>
</template>

<script setup>
import { useAuthStore } from './stores/auth'
const authStore = useAuthStore()
</script>