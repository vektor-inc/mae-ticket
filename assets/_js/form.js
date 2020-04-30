;
((document, parent, max, inputs) => {
    let form = document.getElementById(parent);
    if(!form) return;

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

        form.addEventListener('reset', (e)=>{
            Array.prototype.forEach.call(
                numbers,
                (elem) => {
                    elem.classList.remove('ok', 'error')
                }
            )
            numbers[0].select()
            numbers[0].focus()
        }, false)

        window.addEventListener('DOMContentLoaded', ()=>{
            numbers[0].select()
            numbers[0].focus()
        }, false)
    }
})(document, 'maetic_form', 4, ['number-1','number-2','number-3','number-4'])
;
((document) => {
    window.addEventListener('load', () => {

        let parent = document.getElementById('maetic_code_page')
        if(!parent) return;

        Array.prototype.forEach.call(
            parent.getElementsByClassName('ticket'),
            (ticket) => {
                let number = ticket.getElementsByClassName('_number')
                if (!number) return;

                number = number[0]
                let plus = null
                let minas = null

                let updateValue = () => {
                    if(number.value >= number.max){
                        number.value = number.max
                        plus.setAttribute('disabled', 'disabled')
                        plus.classList.add('disabled')
                    }else{
                        plus.removeAttribute('disabled')
                        plus.classList.remove('disabled')
                    }
                    if(number.value <= number.min){
                        number.value = number.min
                        minas.setAttribute('disabled', 'disabled')
                        minas.classList.add('disabled')
                    }else{
                        minas.removeAttribute('disabled')
                        minas.classList.remove('disabled')
                    }
                }

                let buttons = ticket.getElementsByClassName('_control');
                let role = '';
                for(let i=0;i<buttons.length;i++){
                    let role = buttons[i].getAttribute('role')
                    if(role=='plus') plus = buttons[i];
                    if(role=='minas') minas = buttons[i];
                }

                plus && plus.addEventListener('click', (e)=>{
                    e.preventDefault();
                    number.value = Number(number.value) + 1
                    updateValue()
                }, false)

                minas && minas.addEventListener('click', (e)=>{
                    e.preventDefault();
                    number.value = Number(number.value) - 1
                    updateValue()
                }, false)

                number.addEventListener('change', updateValue, false)
                updateValue()
            }
        );
    }, false)
})(document);