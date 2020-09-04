<?php namespace Qcsoft\App\Api;

class Home
{
    protected function bestsellerProductIds()
    {
        return [1022, 1023, 1024, 1025, 1026, 1027, 1028];
    }

    protected function bestsellerBundleIds()
    {
        return [6, 7, 8, 10, 11, 12];
    }

    protected function homeMainCarousel()
    {
        return [
            [
                'image'   => 'http://mrwshop.tk/themes/myrealway/assets/c2e7fed57db7aae72b638b962f9e297c.png',
                'caption' => <<<EOT
                    <div class="carousel-labels">
                        <div class="carousel-label-1">
                            NATŪRALUS PLATAUS VARTOJIMO<br>
                            SPEKTRO <strong>ANTIPARAZITINIS</strong> KOMPLEKSAS
                        </div>
                        <ul class="carousel-label-2">
                            <div>- Profilaktika ir organizmo valymas</div>
                            <div>- <strong>100%</strong> augalų ekstraktai</div>
                        </ul>
                    </div>
EOT,
            ], [
                'image'   => 'http://mrwshop.tk/themes/myrealway/assets/c2e7fed57db7aae72b638b962f9e297c.png',
                'caption' => <<<EOT
                    <div class="carousel-labels">
                        <div class="carousel-label-1">
                            NATŪRALUS PLATAUS VARTOJIMO<br>
                            SPEKTRO <strong>ANTIPARAZITINIS</strong> KOMPLEKSAS
                        </div>
                        <ul class="carousel-label-2">
                            <div>- Profilaktika ir organizmo valymas</div>
                            <div>- <strong>100%</strong> augalų ekstraktai</div>
                        </ul>
                    </div>
EOT,
            ],
        ];
    }

}
