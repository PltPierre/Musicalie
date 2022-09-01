jQuery(document).ready(function () {
    document.querySelectorAll('.add_item_link')
        .forEach(btn => {
            btn.addEventListener("click", addFormToCollection)
        });
});

// Permet d'ajouter dynamiquement des données dans un formulaire
const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('div');
    item.classList.add('me-4');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    // création du bouton pour supprimer
    const btn = document.createElement('button');
    btn.classList.add('btn', 'btn-sm', 'btn-danger');
    btn.setAttribute('type','button');
    btn.textContent = 'supprimer';
    item.append(btn);

    btn.addEventListener('click',function(){
        item.remove();
    });

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;
};