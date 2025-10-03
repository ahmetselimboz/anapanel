@extends('backend.layout')

@section('content')
    <section class="content">
        <div class="row">

            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">SEO DENETLEME</h4>
                    </div>


                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th>Haber</th>
                                    <th>Sorunlar</th>
                                    <th>İşlem</th>
                                </tr>

                                @foreach($seochecks as $post)
                                    <tr>
                                        <th scope="row">{{ $post->post_id }}</th>
                                        <td>{{ html_entity_decode($post->title) }}</td>
                                        <td>
                                            @if($type==1)
                                                <span class="badge badge-warning">Meta Başlık</span>
                                            @elseif($type==2)
                                                <span class="badge badge-warning">Meta Açıklama</span>
                                            @elseif($type==3)
                                                <span class="badge badge-warning">Etiket</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('post.edit', $post->id) }}" type="button" class="waves-effect waves-light btn btn-secondary btn-sm pull-left" data-bs-toggle="tooltip" data-bs-placement="top" title="Düzenle"><i class="fa fa-pencil"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
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


