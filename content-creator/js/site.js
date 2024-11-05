// Call this from the developer console to control both instances  
var calendars = {};  

$(document).ready(function () {  
    // Assuming you've got the appropriate language files,  
    // Clndr will respect whatever moment's language is set (e.g., moment.lang('ru')).  
    
    // Set the current month in the format YYYY-MM  
    var thisMonth = moment().format('YYYY-MM');  

    // Define event data for the calendar  
    var eventArray = [  
        { startDate: thisMonth + '-10', endDate: thisMonth + '-14', title: 'Multi-Day Event' },  
        { startDate: thisMonth + '-23', endDate: thisMonth + '-26', title: 'Another Multi-Day Event' }  
    ];  

    // Initialize the first calendar instance  
    calendars.clndr1 = $('.cal1').clndr({  
        events: eventArray,  
        clickEvents: {  
            click: function (target) {  
                // Check if clicked date is valid  
                if ($(target.element).hasClass('inactive')) {  
                    console.log('Not a valid datepicker date.');  
                } else {  
                    console.log('VALID datepicker date: ' + moment(target.date).format('YYYY-MM-DD'));  
                }  
            },  
            nextMonth: function () {  
                console.log('Moved to next month.');  
            },  
            previousMonth: function () {  
                console.log('Moved to previous month.');  
            },  
            onMonthChange: function () {  
                console.log('Month changed to: ' + moment(this.getCurrentMonth()).format('MMMM YYYY'));  
            },  
            nextYear: function () {  
                console.log('Moved to next year.');  
            },  
            previousYear: function () {  
                console.log('Moved to previous year.');  
            },  
            onYearChange: function () {  
                console.log('Year changed to: ' + this.getCurrentYear());  
            }  
        },  
        multiDayEvents: {  
            startDate: 'startDate',  
            endDate: 'endDate'  
        },  
        showAdjacentMonths: true,  
        adjacentDaysChangeMonth: false  
    });  

    // Initialize the second calendar instance (uncomment to use)  
    calendars.clndr2 = $('.cal2').clndr({  
        events: eventArray,  
        startWithMonth: moment().add(1, 'months'), // Start with next month  
        clickEvents: {  
            click: function (target) {  
                console.log('Clicked date on clndr2: ' + moment(target.date).format('YYYY-MM-DD'));  
            }  
        }  
    });  

    // Bind both calendars to the left and right arrow keys  
    $(document).keydown(function (e) {  
        if (e.keyCode === 37) { // Left arrow  
            calendars.clndr1.back();  
            calendars.clndr2.back(); // Uncomment if you want to navigate both calendars  
        } else if (e.keyCode === 39) { // Right arrow  
            calendars.clndr1.forward();  
            calendars.clndr2.forward(); // Uncomment if you want to navigate both calendars  
        }  
    });  
});