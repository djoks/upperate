<div wire:ignore x-data="DateChanged('{{ $id }}', '{{ $date }}')" x-on:date-updated-{{ $id }}="updateDate($event.detail[0].newDate)">
    <span x-ref="date" class="capitalize"></span>
</div>

@script
    <script>
        Alpine.data('DateChanged', (id, value) => {
            return {
                id: id,
                date: value,
                dateDisplay: '',
                init() {
                    setInterval(() => {
                        this.dateDisplay = moment(this.date).fromNow();
                        this.$refs.date.innerText = this.dateDisplay;
                    }, 1000);
                },
                updateDate(val) {
                    this.date = val || 0;
                    this.dateDisplay = moment(this.date).fromNow();
                    this.$refs.date.innerText = this.dateDisplay;
                }

            }
        })
    </script>
@endscript
