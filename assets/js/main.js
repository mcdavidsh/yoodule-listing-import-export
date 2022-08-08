const formSerialize = (formselector) => {
    let myform = document.querySelector(formselector),
        forment = new FormData(myform).entries()

    return Object.assign(...Array.from(forment, ([x, y]) => ({[x]: y})))
}

const basename = (path) => {
    return path.split("/").reverse()[1]
}
function serializeObject(obj) {
    var jsn = {};
    $.each(obj, function() {
        if (jsn[this.name]) {
            if (!jsn[this.name].push) {
                jsn[this.name] = [jsn[this.name]];
            }
            jsn[this.name].push(this.value || '');
        } else {
            jsn[this.name] = this.value || '';
        }
    });
    return jsn;
}


let $=jQuery;

jQuery(document).ready( ()=> {


        $('input[name="saveorder"]').on('click', (e) => {
          let data =  $("form").serializeArray();
            let formData = serializeObject(data)
            console.log(formData)
            $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    method: 'POST',
                    data: {action: 'post_booking', check_out_data: formData},
                    dataType: 'json',
                    async: false,
                    beforeSend: (() => {
                        console.log("Please wait")
                    })
                }).done((resp) => {
                    let response = JSON.parse(resp)
                    console.log(resp)
                    if (response.status  === "Confirmed") {
                        alert("Reservation Book Successfully")
                        e.returnValue = true

                    } else if (response.status === "Unconfirmed") {
                        alert("Reservation Book Unconfirmed")
                        e.preventDefault()
                    } else {
                        alert("Reservation Book Failed")
                        e.preventDefault()
                    }
                })

        })

})
