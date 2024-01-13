@if($data)
    <md-list id='scrollable-list' style="background-color: white;">
        @foreach($data as $item)
            <md-elevated-card class="elevated-card-item">
                <md-item class="card-item">
                    <div slot="headline" class="card-headline-text">
                        <strong>{{ $item['name'] }}</strong>
                    </div>
                    <div slot="supporting-text" class="card-supporting-text">
                        {{ $item['temperament'] }}
                    </div> 
                </md-item>
            </md-elevated-card>
        @endforeach
    </md-list>
@else
    <p>No data available.</p>
@endif