<div x-data="{ date: '{{ $date }}', dateDisplay: '' }" x-init="
         dateDisplay = moment(date).fromNow();
         setInterval(() => { 
             dateDisplay = moment(date).fromNow();
         }, 1000);
     ">
    <span class="capitalize" x-text="dateDisplay"></span>
</div>
