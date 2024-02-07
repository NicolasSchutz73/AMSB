// Sélectionner le bouton, l'input de fichier et le conteneur d'aperçu
const btnImage = document.getElementById('btn_image');
const fileInput = document.getElementById('fileInput');
const previewContainer = document.getElementById('previewContainer');

// Ajouter un écouteur d'événement pour ouvrir la boîte de dialogue de fichier
btnImage.addEventListener('click', function() {
    fileInput.click();
});

// Écouteur d'événement pour l'input de fichier
fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();

        reader.addEventListener('load', function() {
            const result = this.result;
            const previewElement = document.createElement('div');
            previewElement.className = 'relative';

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = result;
                img.className = 'h-20 w-20 object-cover rounded-lg';
                previewElement.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = result;
                video.className = 'h-20 w-20 object-cover rounded-lg';
                video.controls = true;
                previewElement.appendChild(video);
            }

            const closeButton = document.createElement('button');
            closeButton.innerHTML = '×';
            closeButton.className = 'absolute top-0 right-0 bg-red-600 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs';
            closeButton.onclick = function() {
                previewContainer.removeChild(previewElement);
                fileInput.value = ''; // Réinitialiser l'input de fichier
            };

            previewElement.appendChild(closeButton);
            previewContainer.appendChild(previewElement);
        });

        reader.readAsDataURL(file);
    }
});
