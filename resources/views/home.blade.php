<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    @if(session()->has('message'))
        <div class="bg-green-800 text-white p-2 rounded-lg w-full mb-4" id="home_message">
            {{ session('message') }}
        </div>
    @endif

    <div class="main-card">

        Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function (){
            const messageElement = document.querySelector("#home_message");
            if(messageElement){
                setTimeout(()=>{
                    messageElement.remove();
                }, 3000);
            }
        });
    </script>

</x-layouts.auth-layout>
