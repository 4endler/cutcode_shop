import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/sass/main.sass','resources/js/app.js'],
      refresh: true,
    }),
  ],
  css: {
    postcss: './postcss.config.cjs' // Явно указываем путь к конфигу
  }
})