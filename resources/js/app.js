import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import {livewire_hot_reload} from 'virtual:livewire-hot-reload'

window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();

livewire_hot_reload();