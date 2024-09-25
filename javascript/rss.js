$(document).ready(function noticiasRss() {
        const Url = './noticia.xml'
        $.ajax({
            url: Url,
            type: 'GET',
            dataType: "xml",
            
            success: function (xml) {
    
            $.each($("item", xml), function(i, e) {
    
                var blogNumber = i + 1 + ". ";
    
                var itemURL = ($(e).find("link"));
                var blogURL = "<a href='" + itemURL.text() + "'>" + itemURL.text() +"</a>";
    
                var itemTitle = ($(e).find("title"));
                var blogTitle = "<h4>" + blogNumber + itemTitle.text() + "</h4>";
    
                $("#notic").append(blogTitle);
                $("#notic").append(blogURL);
    
            });
        },
        error: function (xhr, status) {
            alert('Ocorreu um erro.');
        }
    })
})

//corrigido.!!!!!!!!!!!!!!.....