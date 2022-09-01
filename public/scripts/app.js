async function jsonFetch(url){
    const response = await fetch(url,{
        headers:{
            Accept: 'application/json'
        }
    })
    if(response.status === 204){
        return null;
    }
    const data = await response.json()
    if(response.ok){
        return data
    }
    throw data
}

function bindSelect(select){
    new TomSelect(select, {
        hideSelected: true,
        closeAfterSelect: true,
        preload:true,
        valueField:select.dataset.value,
        labelField:select.dataset.label,
        searchField:select.dataset.label,
        plugins:{
            remove_button : {title:'Supprimer cet élément'}
        },
        load: async (query, callback) => {
            const url = `${select.dataset.remote}?q=${encodeURIComponent(query)}`
            callback(await jsonFetch(url))
        }
    })
}

Array.from(document.querySelectorAll('select[multiple]')).map(bindSelect)