$(document).ready(function () {
    //esta galeria toda é feita dinamica via ajax/api.

    //aqui pego a url da api do unsplash
    const search = 'web';// posso escolher que tipo de imagens quero receber da api
    const client = 'wNoxulbI1obY0x3ZfX20Lywc9kRbkKQDvx0BEAzkiTw'; // minha senha para fazer a requisição;
    const url = `https://api.unsplash.com/search/photos?query=${search}&client_id=${client}`; // a url da api com inf que desejo
    const galeria = document.getElementById('galeria')

    function putDom(imgid) {
        //esta função cria as imagens(img) dentro do link(a) 
        //e ja seta atributos para o fancybox e bostrap 3
        //ja agora estou orgulhoso desta função
        imgid.results.forEach(element => {
            const link = document.createElement('a')
            link.setAttribute('class', 'fancybox');
            link.setAttribute('data-fancybox', 'Galeria');
            const img = document.createElement('img')
            link.href = element.urls.small
            img.src = element.urls.thumb
            link.append(img)
            galeria.append(link)
        })
    }
    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            console.log(data)
            const img = data
            putDom(img)
        },
        error: function (error) {
            console.log('deu erro' + error)
            $('#galeria').html('... o servidor esta fora de area, voltamos em breve').css('color', 'red');
        }
    })
})
//corrigido