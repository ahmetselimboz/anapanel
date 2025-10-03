@extends('backend.layout')


@section('content')
<div class="modal fade" id="notifyModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md">
        <div class="modal-content rounded-3">
            <div class="modal-header bg-success">
                <h4  class="text-center m-2 modal-title" ></h4>
                <button type="button" id="close-model" class="btn-close text-white" data-bs-dismiss="modal"
                    aria-label="Kapat"></button>
            </div>
            <hr class="mt-0">
            <div>
                  <div class="mb-2 px-4">
                        <small class="text-muted d-flex ">
                            <strong class='me-1'>Kime:</strong> <span class="modal-customer me-4"></span><br>
                        
                             <strong class='me-1'>Oluşturulma Tarihi:</strong> <span  class="modal-date"></span>
                        </small>
                </div>
                <div class="modal-message p-4">
                    </div>
              
            </div>
            <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary py-1 my-0 " data-bs-dismiss="modal">Kapat</button>
                  
                    <!--<a href="http://musteri.medyayazilimlari.com" class="btn btn-sm py-1 btn-info d-flex align-items-center" target="_blank">-->
                    <!--    <i data-feather="shopping-cart" class="me-2" style="width: 20px"></i>-->
                    <!--    Satın Al-->
                    <!--</a>-->
                </div>

        </div>
    </div>
</div>
    <section class="content">
        <div class="row">

            <div class="col-12">
                <div class="box">
                   <div class="box-header with-border">
                        <h4 class="box-title">Bildirimler</h4>
                        <div class="box-controls pull-right">
                            <div class="btn-group">
                               
                                  <a href="{{ route('notification.create.page')}}" type="button" class="btn btn-success btn-sm">
                                    <i class="fa fa-rss"></i> Bildirim Yayınla
                                </a>
                            
                            </div>
                        </div>
                    </div>


                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                         <div class="table-responsive mx-2">
                            <table class="table table-hover">
                                <tr>
                                    <th>ID</th>
                                    <th>Kime</th>
                                    <th>Başlık</th>
                                    <th>İçerik</th>
                            
                                    <th>Oluşturma Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                                @forelse($notifications as $item)
                                <tr>
                                    <td>{{ $item['id'] }}</td>
                                    <td>{{ $item->customer ? $item->customer->title : 'Tümü' }}</td>
                                    <td>{{ $item['title'] }}</td>
                                    <td>
                                        {{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($item['message'])), 40) }}
                                    </td>
                                          <td><span class="text-muted">{{ $item['created_at_formatted'] }}</span></td>
                                    <td style="min-width: 150px;">
                                               <button class="btn btn-primary btn-sm  box-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#notifyModal"
                                                    data-title="{{ $item['title'] }}"
                                                    data-message="{{ $item['message'] }}"
                                                    data-customer="{{ $item->customer ? $item->customer->title : 'Tümü' }}"
                                                    data-date="{{ $item['created_at_formatted'] }}"
                                   
                                                >
                                                    <i class="fa fa-eye"></i> 
                                                </button>
                                                <a href="{{route('notification.update.page', ['id'=>$item['id']])}}" class="btn btn-success btn-sm"> <i class="fa fa-refresh"></i> </a>
                                                <a href="{{route('notification.delete', ['id'=>$item['id']])}}" class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i> </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fa fa-envelope fa-2x d-block mb-2"></i>
                                        Henüz bir bildirim yayınlanmamış
                                    </td>
                                </tr>
                            @endforelse
                            </table>
                        
                        </div>
                       
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>


        </div>
    </section>
@endsection

@section('custom_js')
<script>
      $(document).ready(function () {
          $(".box-btn").on('click', function () {
               let title = $(this).data('title');
               let  message = $(this).data('message');
                let customer = $(this).data('customer');
     
                let date = $(this).data('date');
                
                $(".modal-title").text(title)
                $(".modal-message").html(message)
                 $(".modal-customer").text(customer)
           
                 $(".modal-date").text(date)
          })
      })
</script>
@endsection 


