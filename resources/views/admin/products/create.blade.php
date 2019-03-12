@extends('layout.main')

@section('title', 'Admin - Home')

@section('content')
<main class="mn-inner">
    <div class="middle-content row">
        <div class="col s12 m7 l7">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">add product</span><br>
                    <div class="row">
                        <form class="col s12" id="language-create-form">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="name" type="text" class="validate" name="name" >
                                    <label for="name">სახელი</label>
                                </div>
                                <div class="input-field col s12">
                                    <input id="short" type="text" class="validate" name="short" >
                                    <label for="short">მოკლედ</label>
                                </div>
                                <div class="input-field col s12">
                                    <input id="url_code" type="text" class="validate" name="url_code" >
                                    <label for="url_code">URL კოდი</label>
                                </div>
                                <div class="col s12" >
                                    <p class="p-v-xs">
                                        <input type="checkbox" id="test6"  name="status" value="1" checked="checked" />
                                        <label for="test6">აქტიური</label>
                                    </p>
                                </div>
                                <div class="col l12 s12 m12">
                                    <button class="waves-effect waves-blue btn-flat right" id="language-create-btn-submit" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> გთხოვთ დაელოდოთ"><i class="material-icons">check</i> შენახვა</button>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript">
   /* $(document).ready(function() {*/
        form = $('#language-create-form');
        url = '{{route("language-create-post")}}';
        redirect_url = '{{route("languages-all")}}';
        create_language(form, url, redirect_url);
   // });

        

</script>

@stop