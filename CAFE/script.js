
function create_slider(slider_container){
    const l_arrow = document.querySelector(slider_container+' > #left-arrow');
    const r_arrow = document.querySelector(slider_container+' > #right-arrow');
    const slider = document.querySelectorAll(slider_container+' > .slider li');
    const total_items = document.querySelectorAll(slider_container+' > .slider > li').length;

    let counter = 0;

    slider.forEach(li =>{
        li.style.transition= ' .3s ease-in-out';
    })

    const distance = 9.5
    if (total_items>1){

        r_arrow.addEventListener('click', () =>{
            l_arrow.style.display= 'block';

            counter ++;
            console.log(counter);
            slider.forEach(li =>{
                li.style.transform= 'translateX(' + (-distance * counter) +'em)';

            if (counter == total_items-2){
                r_arrow.style.display= 'none';
            }
            })


        })


        l_arrow.addEventListener('click', () =>{
            r_arrow.style.display= 'block';
            
            counter --
            console.log(counter);
            slider.forEach(li =>{
                li.style.transform= 'translateX(' + (-distance * counter) +'em)';

            if (counter == (-total_items+2)){
                l_arrow.style.display= 'none';

            }
            })

        })
    }else{
            r_arrow.style.display= 'none';
            l_arrow.style.display= 'none';

    }


}

create_slider('.slider-1');
create_slider('.slider-2');


const ham_menu = document.querySelector('.ham-btn');
ham_menu.addEventListener('click', ()=>{
    
});

