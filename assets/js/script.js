$( document ).ready(function() {
    var w = window.innerWidth;
   
    if(w > 767){
        $('#menu-jk').scrollToFixed();
    }else{
        $('#menu-jk').scrollToFixed();
    }
    
})




$(document).ready(function(){

    $(".filter-button").click(function(){
        var value = $(this).attr('data-filter');
        
        if(value == "all")
        {
            //$('.filter').removeClass('hidden');
            $('.filter').show('1000');
        }
        else
        {
//            $('.filter[filter-item="'+value+'"]').removeClass('hidden');
//            $(".filter").not('.filter[filter-item="'+value+'"]').addClass('hidden');
            $(".filter").not('.'+value).hide('3000');
            $('.filter').filter('.'+value).show('3000');
            
        }
    });
    
    if ($(".filter-button").removeClass("active")) {
$(this).removeClass("active");
}
$(this).addClass("active");

});

/*time slot */
document.addEventListener("DOMContentLoaded", function () {
    let period = "AM";
    let selectedTime = null;

    const timeOptions = {
        "AM": ["10:00", "11:00"],
        "PM": ["13:00", "15:00", "16:00"]
    };

    const periodDisplay = document.getElementById("am-pm");
    const timeOptionsContainer = document.getElementById("time-options");
    const selectedTimeInput = document.getElementById("selectedTimeInput");

    function updateTimes() {
        timeOptionsContainer.innerHTML = "";
        timeOptions[period].forEach(time => {
            const btn = document.createElement("button");
            btn.classList.add("time-btn");
            btn.textContent = time;
            btn.type = "button";
            btn.disabled = false;

            btn.addEventListener("click", function () {
                document.querySelectorAll(".time-btn").forEach(b => b.classList.remove("selected"));
                btn.classList.add("selected");
                selectedTime = time + " " + period;
                selectedTimeInput.value = selectedTime;  // Set the hidden input value
            });

            timeOptionsContainer.appendChild(btn);
        });
    }

    document.getElementById("prev").addEventListener("click", function () {
        period = period === "AM" ? "PM" : "AM";
        periodDisplay.textContent = period;
        updateTimes();
    });

    document.getElementById("next").addEventListener("click", function () {
        period = period === "AM" ? "PM" : "AM";
        periodDisplay.textContent = period;
        updateTimes();
    });

    updateTimes();
});
