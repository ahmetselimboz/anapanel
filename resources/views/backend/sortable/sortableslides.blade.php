@extends('backend.layout')

@section('custom_css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Ana Manşet Haberleri Sıralama</h4>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        @if(count($slides)>0)
                            <ul id="sortable" class="list-style-none ps-2 pe-2">
                                @foreach($slides as $slide)
                                    <li class="ui-state-default my-2 py-1" id="{{ $slide->id }}">
                                        <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                        <img src="{{ asset('uploads/'.$slide->images) }}" style="max-width: 10%;margin-right: 10px;">
                                     {{ $loop->iteration }} .   {{ $slide->title }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="box-inverse box-warning my-3 mx-4">
                                <div class="box-body mb-1 py-50 text-center">
                                    <i class="fa fa-bullhorn fa-4x"></i>
                                    <h4>Ana Manşette gösterilmek üzere haber seçimi yapılmamış</h4>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>


@endsection

@section('custom_js')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $( function() {
            $( "#sortable" ).sortable({
                stop: function () {
                    let  sortedData = [];
                    $.map($(this).find('li'), function(el) {
                        var itemID = el.id;
                        var itemIndex = $(el).index();
                        sortedData.push({ itemID: itemID, itemIndex: itemIndex });
                    });

                    $.ajax({
                        url: '{{ route("sortableSlidePost") }}',
                        type: 'POST',
                        dataType: 'json',
                        data: { sortedData: sortedData }, // Dizi olarak gönder
                        success: function(response) {
                            console.log('Sıralama başarıyla kaydedildi');
                        },
                        error: function(xhr, status, error) {
                            console.error('Sıralama kaydedilirken hata oluştu:', error);
                        }
                    })
                }
            });
            $( "#sortable" ).disableSelection();
        } );
    </script>
@endsection




