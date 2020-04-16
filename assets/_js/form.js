;
((document, parent, max, inputs) => {
    if(!document.getElementById(parent)) return;

    let
        numbers = [],
        valid = (t)=>{
            if (Number.isInteger(Number(t.value)) && t.value.length == max) {
                t.classList.add('ok')
                t.classList.remove('error')
            }else{
                t.classList.remove('ok')
                t.classList.add('error')
            }
        }
    ;

    Array.prototype.forEach.call(
        inputs,
        (i) => numbers.push(document.getElementById(i))
    )

    for(let i=0;i<numbers.length;i++){
        if(!numbers[i]) return;

        numbers[i].setAttribute('digit', i)
        numbers[i].setAttribute('min', 0)
        numbers[i].setAttribute('max', Math.pow(10,max)-1)
        numbers[i].setAttribute('required', 'required')
        numbers[i].addEventListener('keyup', (e)=>{
            let t = e.target
            let digit = Number(t.getAttribute('digit'))

            if(48 <= e.keyCode && e.keyCode <= 57) {
                valid(t)
                if (t.value.length == max && digit < numbers.length-1) {
                    numbers[digit+1].focus()
                    numbers[digit+1].select()
                }
            }
            if(e.keyCode == 37 && digit != 0){
                numbers[digit-1].focus()
            }
            if(e.keyCode == 39 && digit < numbers.length-1){
                numbers[digit+1].focus()
            }
        })

        numbers[i].addEventListener('keydown', (e)=>{
            let t = e.target
            let digit = Number(t.getAttribute('digit'))

            if(48 <= e.keyCode && e.keyCode <= 57) {
                if(t.value.length == max)e.preventDefault()
            }
            if([189,69,190,229].includes(e.keyCode)){
                e.preventDefault()
            }
            if(e.keyCode == 8) {
                if(t.value.length == 0 && digit > 0) {
                    t.value = '';
                    t.classList.remove('ok', 'error')
                    numbers[digit-1].focus()
                }
            }
        })

        numbers[i].addEventListener('focus', (e)=>{
            if(e.target.value!=''){
                e.target.select()
            }
        })
    }
})(document, 'maetick_input', 4, ['number-1','number-2','number-3','number-4'])
;