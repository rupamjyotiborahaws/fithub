import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
			'resources/css/app.css',
			'resources/css/admin.css',
                	'resources/css/toastr.css',
                        'resources/js/jquery-3.7.1.min.js',
			'resources/js/app.js',
	                'resources/js/toastr.min.js',
                        'resources/js/admin_dashboard.js',
                        'resources/js/layout.js'
		   ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
