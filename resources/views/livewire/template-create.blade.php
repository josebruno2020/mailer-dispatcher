<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Templates') }}
        </h2>
    </x-slot>

    <x-content>
        <form wire:submit.prevent="save">
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <x-label for="name" value="{{ __('Name') }}" />
                        <x-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="form.name" />
                        <x-input-error for="name" class="mt-2" :messages="$errors->get('form.name')" />
                    </div>
                    <div>
                        <x-label for="subject" value="{{ __('Subject') }}" />
                        <x-input id="subject" type="text" class="mt-1 block w-full" wire:model.defer="form.subject" />
                        <x-input-error for="subject" class="mt-2" :messages="$errors->get('form.subject')" />
                    </div>
                    <div>
                        <x-label for="description" value="{{ __('Description') }}" />
                        <x-input id="description" type="text" class="mt-1 block w-full" wire:model.defer="form.description" />
                        <x-input-error for="description" class="mt-2" :messages="$errors->get('form.description')" />
                    </div>
                </div>

                <!-- HTML Editor with Preview -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- HTML Editor -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <x-label for="body" value="{{ __('HTML Content') }}" />
                            <div class="flex space-x-2">
                                 <button type="button" onclick="insertTag('h1')" class="px-2 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <b>H1</b>
                                </button>
                                <button type="button" onclick="insertTag('p')" class="px-2 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <b>P</b>
                                </button>
                                <button type="button" onclick="insertTag('b')" class="px-2 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <b>B</b>
                                </button>
                                <button type="button" onclick="insertTag('i')" class="px-2 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <i>I</i>
                                </button>
                                <button type="button" onclick="insertTag('u')" class="px-2 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <u>U</u>
                                </button>
                                <button type="button" onclick="insertLink()" class="px-2 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                    Link
                                </button>
                            </div>
                        </div>
                        <textarea 
                            id="body" 
                            wire:model.defer="form.body"
                            class="mt-1 block w-full h-96 font-mono text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            placeholder="Digite seu HTML aqui..."
                            oninput="updatePreview()"
                        ></textarea>
                        <x-input-error for="body" class="mt-2" :messages="$errors->get('form.body')" />
                    </div>

                    <!-- Preview -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <x-label value="{{ __('Preview') }}" />
                            <div class="flex space-x-2">
                                <button type="button" onclick="togglePreviewMode('desktop')" id="desktop-btn" class="px-2 py-1 text-xs bg-blue-500 text-white rounded">
                                    Desktop
                                </button>
                                <button type="button" onclick="togglePreviewMode('mobile')" id="mobile-btn" class="px-2 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded">
                                    Mobile
                                </button>
                            </div>
                        </div>
                        <div class="border border-gray-300 dark:border-gray-700 rounded-md overflow-hidden bg-white">
                            <div id="preview-container" class="w-full transition-all duration-300">
                                <div id="preview-content" class="p-4 h-96 overflow-auto bg-white text-gray-800">
                                    <!-- Preview será exibido aqui -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Available Parameters</h3>
                <div class="">
                    @foreach ($parameters as $parameter)
                        <div class="mb-1 bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 p-1 rounded text-sm">
                            {{ $parameter }}
                        </div>
                    @endforeach
                </div>

            </div>

            <div class="flex items-center justify-end mt-6">
                <x-secondary-button href="{{ route('templates') }}" wire:navigate>
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-primary-button class="ms-3">
                    {{ __('Save') }}
                </x-primary-button>
            </div>
        </form>
    </x-content>

    
    <script>
        const updatePreview = () => {
            try {
                const htmlContent = document.getElementById('body').value;
                const previewDiv = document.getElementById('preview-content');
                
                if (htmlContent.trim() === '') {
                    previewDiv.innerHTML = '<p class="text-gray-500 italic">Digite seu HTML no editor para ver o preview...</p>';
                } else {
                    previewDiv.innerHTML = htmlContent;
                }
                console.log(previewDiv)
                // Atualizar parâmetros via Livewire
                // @this.call('updateParameters', htmlContent);
            } catch (error) {
                console.error('Erro ao atualizar preview:', error);
            }
        }
    
        const insertTag = (tag) => {
            try {
                const textarea = document.getElementById('body');
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const selectedText = textarea.value.substring(start, end);
                
                let insertion;
                if (selectedText) {
                    insertion = '<' + tag + '>' + selectedText + '</' + tag + '>';
                } else {
                    insertion = '<' + tag + '></' + tag + '>';
                }
                
                textarea.value = textarea.value.substring(0, start) + insertion + textarea.value.substring(end);
                
                // Mover cursor
                const newPosition = selectedText ? start + insertion.length : start + tag.length + 2;
                textarea.focus();
                textarea.setSelectionRange(newPosition, newPosition);
                
                updatePreview();
                
                // Disparar evento para Livewire
                textarea.dispatchEvent(new Event('input', { bubbles: true }));
            } catch (error) {
                console.error('Erro ao inserir tag:', error);
            }
        }
    
        const insertLink = () => {
            try {
                const url = prompt('Digite a URL:');
                if (url) {
                    const text = prompt('Digite o texto do link:') || url;
                    const textarea = document.getElementById('body');
                    const start = textarea.selectionStart;
                    const insertion = '<a href="' + url + '">' + text + '</a>';
                    
                    textarea.value = textarea.value.substring(0, start) + insertion + textarea.value.substring(textarea.selectionEnd);
                    textarea.focus();
                    updatePreview();
                    
                    // Disparar evento para Livewire
                    textarea.dispatchEvent(new Event('input', { bubbles: true }));
                }
            } catch (error) {
                console.error('Erro ao inserir link:', error);
            }
        }
    
        const togglePreviewMode = (mode) => {
            try {
                const container = document.getElementById('preview-container');
                const desktopBtn = document.getElementById('desktop-btn');
                const mobileBtn = document.getElementById('mobile-btn');
                
                if (mode === 'mobile') {
                    container.style.maxWidth = '375px';
                    container.style.margin = '0 auto';
                    mobileBtn.className = 'px-2 py-1 text-xs bg-blue-500 text-white rounded';
                    desktopBtn.className = 'px-2 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded';
                } else {
                    container.style.maxWidth = '100%';
                    container.style.margin = '0';
                    desktopBtn.className = 'px-2 py-1 text-xs bg-blue-500 text-white rounded';
                    mobileBtn.className = 'px-2 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded';
                }
            } catch (error) {
                console.error('Erro ao alternar modo de preview:', error);
            }
        }
    
        // Inicializar preview quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
        });
    
        // Atualizar preview quando Livewire carregar
        document.addEventListener('livewire:navigated', function() {
            updatePreview();
        });
    </script>
</div>
