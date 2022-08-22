  </main>

  <!-- footer -->
  <footer>
    <div class="block_footer">
      <div id="footer_actions" class="_actions animate__animated"></div>

      <div class="_bottom" id="block_nav_mobile_logo_content">
        <?include 'core/app/templates/elems/soc_block.php'?>

        <div class="_params">
          <div class="_item _theme">
            <div class="block_swich" id="theme_switch">
              <div class="_vals">
                <div class="_val <?=$_SESSION['theme'] == 1 ? '_select_' : ''?>" data-val="1">
                  <i class="fa-solid fa-moon"></i>
                </div>
                <div class="_val  <?=$_SESSION['theme'] == 2 ? '_select_' : ''?>" data-val="2">
                  <i class="fa-solid fa-sun"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="_item _lang">
            <div class="block_swich" id="lang_switch">
              <select class="select _vals" name="lang">
                <option <?=$_SESSION['lang'] == 'en' ? 'selected="selected"' : ''?> value="en">en</option>
                <option <?=$_SESSION['lang'] == 'ru' ? 'selected="selected"' : ''?> value="ru">ru</option>
              </select>
            </div>
          </div>
        </div>

        <div class="_copy">
          2021 - <?=date('Y')?> <a href="https://trywar.ru/" target="_blank">TrywaR [dev]</a> ©
        </div>
      </div>
    </div>
  </footer>

  <!-- Forms -->
  <div id="fttm_modal" class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- loader -->
  <div id="loader" class="loader">
    <div class="_full">
      <svg width="200" height="100" viewBox="0 0 1000 305" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M514.798 297.908V4.01803H464.094V297.908H514.798Z" fill="#212128"/>
        <path d="M624.425 297.908V90.884H573.721V297.908H624.425Z" fill="#212128"/>
        <path d="M577.931 17.9855C572.828 22.9602 570.277 29.2104 570.277 36.7363C570.277 44.1346 572.828 50.3211 577.931 55.2958C583.161 60.2705 590.176 62.7578 598.978 62.7578C607.779 62.7578 614.731 60.2705 619.833 55.2958C625.063 50.3211 627.678 44.1346 627.678 36.7363C627.678 29.2104 625.063 22.9602 619.833 17.9855C614.731 13.0108 607.779 10.5234 598.978 10.5234C590.176 10.5234 583.161 13.0108 577.931 17.9855Z" fill="#212128"/>
        <path d="M695.785 297.908H746.68V127.238H788.583V90.884H746.68V71.7505C746.68 61.546 749.487 53.7651 755.099 48.4077C760.712 43.0503 768.684 40.3716 779.016 40.3716C782.46 40.3716 785.585 40.4992 788.391 40.7543C791.325 41.0094 794.004 41.4559 796.427 42.0937L797.384 3.63536C792.92 2.48735 788.391 1.59446 783.799 0.956674C779.335 0.318891 774.615 0 769.641 0C746.935 0 728.95 6.18649 715.684 18.5595C702.418 30.8049 695.785 48.5353 695.785 71.7505V90.884H664.598V127.238H695.785V297.908Z" fill="#212128"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M861.856 288.533C877.035 297.334 895.085 301.735 916.004 301.735C936.285 301.735 952.995 297.781 966.134 289.872C979.4 281.964 989.285 273.226 995.791 263.659L970.343 237.255C963.965 245.419 956.439 251.605 947.766 255.815C939.219 259.896 929.653 261.937 919.065 261.937C907.968 261.937 898.401 259.322 890.365 254.093C882.329 248.735 876.206 241.528 871.997 232.472C868.818 225.537 866.84 217.947 866.062 209.703H1000V188.465C1000 168.311 996.62 150.644 989.859 135.465C983.099 120.286 973.022 108.423 959.628 99.8768C946.362 91.3305 929.908 87.0573 910.264 87.0573C891.386 87.0573 874.739 91.5218 860.326 100.451C845.912 109.38 834.687 121.88 826.651 137.952C818.742 153.897 814.788 172.52 814.788 193.822V201.476C814.788 220.226 818.87 237.191 827.033 252.371C835.197 267.55 846.805 279.604 861.856 288.533ZM866.912 174.88C867.709 169.135 868.894 163.777 870.466 158.808C873.655 148.731 878.502 140.886 885.008 135.274C891.641 129.661 899.932 126.855 909.881 126.855C919.193 126.855 926.783 128.896 932.65 132.978C938.518 136.932 942.855 142.289 945.661 149.05C948.467 155.683 949.998 163.017 950.253 171.053V174.88H866.912Z" fill="#212128"/>
        <path d="M126.281 276.394C122.349 281.28 117.821 285.581 112.696 289.298C101.216 297.589 86.5471 301.735 68.6892 301.735C48.2802 301.735 31.6978 295.74 18.9421 283.75C6.31405 271.759 0 252.052 0 224.627V90.884H50.5124V225.01C50.5124 234.066 51.9155 241.273 54.7218 246.631C57.528 251.988 61.3547 255.815 66.2019 258.111C71.049 260.279 76.4064 261.363 82.274 261.363C94.5194 261.363 104.15 258.94 111.166 254.093C116.984 250.04 121.449 244.888 124.559 238.635V90.884H175.454V297.908H127.62L126.281 276.394Z" fill="#212128"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M223.886 77.2229C223.886 36.6451 256.781 3.75037 297.359 3.75037H333.987C374.565 3.75037 407.459 36.6452 407.459 77.2229L407.461 231.223C407.461 271.801 374.566 304.696 333.988 304.696H297.36C256.782 304.696 223.887 271.801 223.887 231.223L223.886 77.2229ZM297.359 52.7321C283.833 52.7321 272.868 63.697 272.868 77.2229L272.869 231.223C272.869 244.749 283.834 255.714 297.36 255.714H333.988C347.514 255.714 358.479 244.749 358.479 231.223L358.478 77.2229C358.478 63.697 347.513 52.7321 333.987 52.7321H297.359Z" fill="#6B36FF"/>
        <path d="M407.458 77.2222C407.458 62.7786 403.291 49.3085 396.092 37.9489L358.425 75.6163C358.459 76.1471 358.477 76.6826 358.477 77.2222L358.478 231.223C358.478 244.748 347.513 255.713 333.987 255.713H297.359C283.833 255.713 272.868 244.748 272.868 231.222L272.868 194.355L224.732 242.407C230.119 277.677 260.584 304.695 297.359 304.695H333.987C374.565 304.695 407.459 271.8 407.459 231.223L407.458 77.2222Z" fill="url(#paint0_linear_29_2688)"/>
        <path d="M358.478 112.173L406.343 64.3908C400.273 29.9309 370.187 3.75037 333.987 3.75037H297.359C256.781 3.75037 223.886 36.6451 223.886 77.2229L223.887 231.223C223.887 245.666 228.055 259.136 235.253 270.496L272.921 232.828C272.886 232.297 272.869 231.762 272.869 231.223L272.868 77.2229C272.868 63.697 283.833 52.7321 297.359 52.7321H333.987C347.513 52.7321 358.478 63.697 358.478 77.2229L358.478 112.173Z" fill="url(#paint1_linear_29_2688)"/>
        <path d="M235.255 270.495C228.057 259.136 223.889 245.666 223.889 231.222L223.889 223.681C223.889 215.02 227.33 206.715 233.454 200.591L396.095 37.9489C403.294 49.3085 407.461 62.7786 407.461 77.2222L407.461 84.7624C407.462 93.423 404.021 101.729 397.897 107.853L235.255 270.495Z" fill="#6B36FF"/>
        <defs>
        <linearGradient id="paint0_linear_29_2688" x1="283.5" y1="242.5" x2="401.5" y2="122.5" gradientUnits="userSpaceOnUse">
        <stop stop-color="#6B36FF"/>
        <stop offset="1" stop-color="#3C12B2"/>
        </linearGradient>
        <linearGradient id="paint1_linear_29_2688" x1="234.5" y1="198.5" x2="358.5" y2="74.9995" gradientUnits="userSpaceOnUse">
        <stop stop-color="#3C12B3"/>
        <stop offset="1" stop-color="#6B36FF"/>
        </linearGradient>
        </defs>
      </svg>
      <i class="fa-solid fa-spin fa-spinner"></i>
    </div>

    <div class="_min">
      <i class="fa-solid fa-spin fa-spinner"></i>
    </div>
  </div>
</body>
</html>
