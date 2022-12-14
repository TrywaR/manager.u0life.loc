<section class="welcome_container main_content">
  <div class="_section _title">
    <div class="_block">
      <div class="_content">
        <?include 'core/templates/elems/logo.php'?>
        <p>
          Это симулятор, способный оцифровать жизнь, помогающий более эффективно распоряжаться своим временем и финансами
        </p>
        <p>
          Оставь свой точный след в истории человечества и взгляни на свою жизнь со стороны
        </p>
        <h1 style="visibility:hidden; margin: 0; padding: 0; font-size: .1em;">u0life</h1>
        <div class="_start">
          <?include 'core/templates/elems/logining.php';?>
        </div>
      </div>
    </div>
    <div class="_bg">
      <!-- <video autoplay muted loop id="myVideo">
        <source src="/template/videos/Background_08.mov" type="video/mp4">
      </video> -->
    </div>
  </div>

  <div class="_section _line">
    <span class="_tag">Отслеживание времени</span>
    <span class="_sep">,</span>
    <span class="_tag">Система учёта финансов</span>
    <span class="_sep">,</span>
    <span class="_tag">Тикет система</span>
  </div>

   <div class="_section _times">
     <div class="_block">
         <div class="_icon">
           <i class="fas fa-clock"></i>
         </div>
         <div class="_content">
           <h2 class="_block_title"><?=$oLang->get('Times')?></h2>
           <p>
             Время – невосполнимый ресурс, которого всегда не хватает. Но с помощью этого сервиса Вы легко сможете им управлять. Вы удивитесь, насколько больше времени у Вас будет и насколько продуктивнее станут ваши дела, будь это работа, отдых или сон.
           </p>
           <p>
             Отслеживая затраченное время, Вам будет проще его оптимизировать, вплоть до минуты, что, несомненно, скажется на уровне Вашей жизни.
           </p>
           <p>
             Для этого есть следующие функции:
           </p>

           <ul>
             <li>Разделения затраченного времени по категориям</li>
             <li>Построение графиков затрат времени по различным периодам</li>
             <li>Отслеживание периода на конкретные задачи, с определением планируемого времени и реально затраченного, что позволит отследить, насколько точно Вы планируете затраты по времени на задачи, и улучшить этот навык.</li>
           </ul>

           <!-- <div class="_link">
             <a href="/info/docs/times/" title="<?=$oLang->get('MoreInDocs')?>"><?=$oLang->get('More')?></a>
           </div> -->
         </div>
       </div>
   </div>

   <div class="_section _moneys">
     <div class="_block">
       <div class="_icon">
         <i class="fas fa-wallet"></i>
       </div>

       <div class="_content">
         <h2 class="_block_title"><?=$oLang->get('Moneys')?></h2>
         <p>Этот сервис поможет эффективно распоряжаться вашими финансами за счёт их полного анализа. </p>
         <p>Детальное отслеживание всех финансовых потоков и разделение их по неограниченным количествам категорий, такие как: наличные, кредитные карты, банковские счета, кэшбэк и прочее. Такой анализ поможет Вам не только улучшить контроль за поступающими деньгами, но и планировать будущие покупки. </p>
         <p>Для этого есть следующие функции:</p>
         <ul>
          <li>Разделения финансов по категориям</li>
          <li>Построение графиков затрат финансов по различным периодам времени</li>
          <li>Затраты и пополнения по отдельным проектам</li>
          <li>Отслеживание ежемесячных платежей и подписок</li>
          <li>Учет переводов между своими картами</li>
          <li>Отслеживание комиссии и просрочек платежей по кредитным картам, начисления по дебетовым.</li>
         </ul>

         <!-- <div class="_link">
           <a href="/info/docs/moneys/" title="<?=$oLang->get('MoreInDocs')?>"><?=$oLang->get('More')?></a>
         </div> -->
       </div>
     </div>
   </div>

   <div class="_section _moneysforhour">
     <div class="_block">
       <div class="_icon">
         <i class="fa-solid fa-clipboard-check"></i>
       </div>
         <div class="_content">
           <h2 class="_block_title">Время и деньги</h2>
            <p>Уникальным инструментом сервиса является объединение анализа затраченных времени и денег, что позволит вам рассчитать стоимость вашего времени в деньгах. </p>
            <p>Например: Сколько стоит ваш час работы, как в общем так и по отдельным проектам. Это позволит урегулировать стоимость своих услуг, учитывая финансовые и временные затраты на работу. </p>
            <p>Такие данные помогают непредвзято оценить картину  проделанной работы, что может служить обоснованием для повышения стоимости своих услуг на рынке труда.</p>
         </div>
       </div>
   </div>

   <div class="_section _tasks">
     <div class="_block">
       <div class="_icon">
         <i class="fa-solid fa-person-digging"></i>
       </div>
       <div class="_content">
         <h2 class="_block_title">Тикет система</h2>
         <p>
           В приложении есть функционал позволяющих вести список дел. Записывать какая задача сколько времени отняла и сколько денег принесла.
           <br>
           Привязка задач к проектам и клиентам позволит легко ориентироваться в поставленных задачах.
         </p>
       </div>
     </div>
   </div>

   <div class="_section _info">
     <div class="_block">
       <!-- <div class="_icon">
         <i class="fa-solid fa-circle-info"></i>
       </div> -->
       <div class="_content">
         <h2 class="_block_title">Инфо.</h2>
          <p>Остались вопросы? <a href="/info/">Подробнее</a></p>
       </div>
     </div>
   </div>

   <?php if ( ! isset($_SESSION['user'])): ?>
     <div class="_section _start">
       <div class="_block">
           <div class="_content">
             <h2 class="_block_title">Начать</h2>
             <p>
               Попробуй, быстрая регистрация без использования электронной почты и номера телефона
             </p>
             <?include 'core/templates/elems/logining.php';?>
           </div>
         </div>
     </div>
   <?php endif; ?>
</section>
