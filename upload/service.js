/* global fetch, URLSearchParams, Jcrop, jcrop*/
const urlParams = new URLSearchParams(window.location.search);
const name = urlParams.get('name');
let url = 'https://informatica.ieszaidinvergeles.org:10053/pia/upload/service.php?name=' + name;

fetch(url)
.then(function(response) {
    return response.json();
})
.then(function (data) {
    console.log('Request succeeded with JSON response', data);
    processResponse(data);
})
.catch(function (error) {
    console.log('Request failed', error);
});

let jcrop = Jcrop.attach('imagen',{
    shadeColor: 'black',
    multi: true
 });
     
function processResponse(data) {
    const imagen = document.getElementById('imagen');
    const height = imagen.height;
    const width = imagen.width;
    let rect;
    for(const cara of data){
        if(cara.low < 18){

            rect = Jcrop.Rect.create(cara.left * width ,cara.top * height ,cara.width * width ,cara.height* height);
            jcrop.newWidget(rect,{});
        }
    }

}

function addInput(name,value){
    let element = document.createElement("input");
    element.name = name + '[]';
    element.type = 'hidden';
    element.value = value;
    element.form = 'fblur';
    fblur.appendChild(element);
}

let fblur = document.getElementById('fblur');
fblur.addEventListener("submit", function(){
    for(const crop of jcrop.crops){
        addInput('x',crop.pos.x);
        addInput('y',crop.pos.y);
        addInput('w',crop.pos.w);
        addInput('h',crop.pos.h);
    }
})
