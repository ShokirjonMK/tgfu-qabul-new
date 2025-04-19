<?php
use common\models\Student;
use common\models\Direction;
use common\models\Exam;
use common\models\StudentPerevot;
use common\models\StudentDtm;
use common\models\Course;
use Da\QrCode\QrCode;
use frontend\models\Contract;
use common\models\User;
use common\models\Consulting;
use common\models\StudentMaster;
use common\models\Branch;

/** @var Student $student */
/** @var Direction $direction */
/** @var User $user */
/** @var Branch $filial */

$user = $student->user;
$phone = preg_replace('/\D/', '', $user->username);
$cons = Consulting::findOne($user->cons_id);
$eduDirection = $student->eduDirection;
$direction = $eduDirection->direction;
$full_name = $student->last_name.' '.$student->first_name.' '.$student->middle_name;
$code = '';
$joy = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$date = '';
$link = '';
$con2 = '';
if ($student->edu_type_id == 1) {
    $contract = Exam::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'status' => 3,
        'is_deleted' => 0
    ]);
    $code = 'Q2/'.$student->passport_serial.$student->passport_number.'/'.$phone;
    $date = date("Y-m-d H:i" , $contract->confirm_date);
    $link = '1&?id='.$contract->id;
    $con2 = '2'.$contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 2) {
    $contract = StudentPerevot::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'P2/'.$student->passport_serial.$student->passport_number.'/'.$phone;
    $date = date("Y-m-d H:i" , $contract->confirm_date);
    $link = '2&?id='.$contract->id;
    $con2 = '2'.$contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 3) {
    $contract = StudentDtm::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'D2/'.$student->passport_serial.$student->passport_number.'/'.$phone;
    $date = date("Y-m-d H:i:s" , $contract->confirm_date);
    $link = '3&?id='.$contract->id;
    $con2 = '2'.$contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 4) {
    $contract = StudentMaster::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'M2/'.$student->passport_serial.$student->passport_number.'/'.$phone;
    $date = date("Y-m-d H:i:s" , $contract->confirm_date);
    $link = '4&?id='.$contract->id;
    $con2 = '2'.$contract->invois;
    $contract->down_time = time();
    $contract->save(false);
}

$student->is_down = 1;
$student->update(false);

$filial = Branch::findOne($student->branch_id);

?>


<table width="100%" style="font-family: 'Times New Roman'; font-size: 12px; border-collapse: collapse;">

    <tr>
        <td colspan="4" style="text-align: center">
            <b>
                Toshkent gumanitar fanlar  universitetida oʻqitish uchun <br>
                toʻlov-kontrakt <br><br>
                SHARTNOMA № <?= $code ?>/<?= $direction->code ?>/<?= $contract->id ?>
            </b>
        </td>
    </tr>

    <tr><td>&nbsp;</td></tr>

    <tr>
        <td colspan="2"><?= $date ?></td>
        <td colspan="2" style="text-align: right"><span>Toshkent shahri</span></td>
    </tr>

    <tr><td>&nbsp;</td></tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            Oliy ta’lim хizmatlarini ko‘rsatish faoliyati uchun 2022-yil 30-dekabrda bеrilgan 222840-raqamli litsеnziya egasi, <b style="text-transform: uppercase;"><?= $filial->univer_name_uz ?></b> (kеyingi o‘rinlarda Ta’lim tashkiloti) nomidan uning Ustaviga muvofiq ish ko‘ruvchi rektor <b style="text-transform: uppercase;"><?= $filial->prorektor_uz ?></b> bir tomondan va
            <b style="text-transform: uppercase;"><?= $full_name ?></b> (kеyingi o‘rinlarda matnda “Talaba” dеb yuritiladi) ikkinchi tomondan, <br>
            _______________________________________________ keyingi o`rinlarda matnda “To`lovchi” deb yuritiladi) __________________ asosida ish yurituvchi
            ____________________________________________ ishtirokida (birgalikda- “Taraflar” deb atalishadi) quyidagilar to‘g‘risida ushbu shartnomani tuzdilar:
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>I. SHARTNOMA PREDMETI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            1.1. Mazkur Shartnomaga asosan Taʻlim tashkiloti Talabani 2025/2026 oʻquv yili davomida <b><?= $direction->code.' '.$direction->name_uz ?></b> ta’lim yo‘nalishi bo‘yicha <b style="text-transform: uppercase"><?= $direction->eduForm->name_uz ?></b> ta’lim shaklida bakalavr qilib tayyorlash dasturi bo’yicha o’quv jarayonini (keyingi o’rinlarda “o’qitish”) amalga oshirish majburiyatini o’z zimmasiga oladi, Talaba/to’lovchi esa shartnomaning 3-bobida koʻrsatilgan tartib va miqdordagi toʻlovni amalga oshirish majburiyatini oladi. Talabaning ta’lim ma’lumotlari quyidagicha:
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="border: 1px solid #000000; padding: 5px;">
            <table width="100%">
                <tr>
                    <td colspan="2">Ta’lim bosqichi:</td>
                    <td colspan="2"><b>Bakalavr</b></td>
                </tr>
                <tr>
                    <td colspan="2">Ta’lim shakli:</td>
                    <td colspan="2"><b><?= $eduDirection->eduForm->name_uz ?></b></td>
                </tr>
                <tr>
                    <td colspan="2">O‘quv bosqichi:</td>
                    <?php if ($student->edu_type_id == 2) : ?>
                        <td colspan="2"><b><?= Course::findOne(['id' => ($student->course_id + 1)])->name_uz ?></b></td>
                    <?php else: ?>
                        <td colspan="2"><b>1 kurs</b></td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <td colspan="2">O’qish muddati:</td>
                    <td colspan="2"><b><?= $eduDirection->duration .' yil' ?></b></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: justify">
                        Ta’lim yo’nalishi: <b><?= $direction->name ?></b> Mazkur shartnoma bo‘yicha to‘lov amalga oshirilgach, bank to‘lov topshiriqnomasi yoki kvitantsiya nusхasi Ta’lim tashkilotiga taqdim etilganidan so‘ng to‘lovning Ta’lim tashkilotining hisob varag‘iga kеlib tushganligi tasdiqlanishi bilan Talabaning o‘qishga qabul qilinganligi to‘g‘risida Ta’lim tashkiloti tomonidan buyruq chiqariladi.
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>II. TOMONLARNING HUQUQ VA MAJBURIYATLARI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <b>2.1. Taʻlim tashkilotining huquqlari:</b>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.1.  Talaba/to’lovchi tomonidan o’z majburiyatlarini bajarilishi ustidan doimiy monitoring olib borish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.2. Mazkur shartnomada kеlishilgan shartlar Talaba/to’lovchi tomonidan bajarilmasa, shartnomani bir taraflama bеkor qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.3. Talaba Taʻlim tashkilotining ichki hujjatlarida belgilangan qoidalarini qoʻpol ravishda buzgan hollarda Shartnomani bir tomonlama bekor qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.4. Mazkur shartnoma bo‘yicha Talaba tomonidan tеgishlicha to‘lov kеchiktirilganda yoki ichki tartib qoidalari buzilganda Talabaga nisbatan intizomiy jazo chorasini qo‘llash, Talabaning o‘quv binosiga kirishi yoki Ta’lim tashkilotining rеsurslaridan foydalanish huquqini chеklash, Talabani talabalar safidan chiqarish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.5. Mehnatga haq toʻlashning eng kam miqdori oʻzgarishi bilan mos ravishda Shartnoma boʻyicha toʻlov miqdorini oʻzgartirish.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.6. Ta’lim хizmatlari soni va sifatini pasaytirmagan holda, tasdiqlangan o‘quv mashg‘ulotlari jadvaliga o‘zgartirishlar kiritish, zarur hollarda o‘quv jarayonini onlayn tarzda tashkil etish, dars mashg‘ulotlarini, oraliq va yakuniy nazoratlarni onlayn o‘tkazish.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.7. Ta’lim tashkiloti qonunchilik hujjatlariga muvofiq boshqa huquqlarga ham ega bo‘lishi mumkin.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            <b>2.2. Taʻlim tashkilotining majburiyatlari:</b>
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.1. Talaba/to’lovchiga doir ma’lumotlarni sir saqlash.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.2. Talabalarning qonunchilik hujjatlarida belgilangan huquqlarini erkin amalga oshirilishini va Ta’lim tashkiloti Ustaviga muvofiq majburiyatlarining bajarilishini ta’minlash.
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.3. Talaba oʻquv yilining birinchi yarmi uchun to‘lov kontrakt summasining kamida 25% ni amalga oshirganidan soʻng uni talabalar safiga qabul qilish.
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.4. Talabani tasdiqlangan malaka talablari, oʻquv reja va dasturlarga muvofiq davlat standartlari talablari darajasida oʻqitish.
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.5. Talaba bakalavriat bosqichining <?= $direction->name ?> yoʻnalishini muvaffaqiyatli tamomlaganda belgilangan tartibda diplom berish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.6. Boshlanadigan o’quv yilida o’qitish uchun to’lovning belgilangan miqdori to’g’risida hamda to’lov miqdori Ta’lim tashkiloti tomonidan o’zgartirilganda Ta’lim tashkilotining rasmiy veb sayti va ijtimoiy tarmoqlardagi rasmiy sahifalarida xabar berish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.7. Imtixonlarni qayta topshirish va boshqa xizmatlar uchun qo’shimcha shartnomalarni Talaba/to’lovchiga taqdim etish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <b>2.3. Talabaning huquqlari:</b>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.3.1. Taʻlim tashkilotidan shartnomaviy majburiyatlari bajarilishini talab qilish.
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: justify">
            2.3.2. Taʻlim tashkilotida tasdiqlangan malaka talablari, oʻquv reja va dasturlarga muvofiq davlat standarti talablari darajasida taʻlim olish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.3.3. Taʻlim tashkilotining Axborot-resurs markazi, sport inshoati, Wi-Fi hududidan foydalanish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.3.4. Taʻlim tashkilotining taʻlim jarayonlarini yaxshilashga doir takliflar berish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.3.5. Oʻqish uchun bir yillik toʻlov summasini bir yo`la toʻliq toʻlash.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.3.6. Qonunchilik hujjatlarida, shuningdеk, Ta’lim tashkilotining lokal hujjatlarida bеlgilangan boshqa huquqlardan foydalanish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <b>2.4. Talabaning majburiyatlari:</b>
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.1. Joriy oʻquv yili uchun belgilangan oʻqitish qiymatini Kontraktning 3-bobida koʻrsatilgan tartib va miqdorda oʻz vaqtida toʻlash.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.2. Taʻlim tashkiloti Ustavi va boshqa ichki hujjatlari, shu jumladan oʻquv tusdagi hujjatlar bilan tanishish va ularning talablariga qat’iy rioya qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.3. Oʻquv mashgʻulotlarida belgilangan tartib va qoidalarga muvofiq ishtirok etish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.4. Etika qoidalariga rioya qilish, tinglovchilar (talabalar) va pеdagoglarga hurmat bilan munosabatda bo‘lish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.5. Ta’lim tashkilotining rasmiy veb sayti va ijtimoiy tarmoqlarini muntazam kuzatib borish hamda Ta’lim tashkilotining ijtimoiy tarmoqlardagi rasmiy sahifalariga a’zo bo‘lish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.6. Taʻlim tashkilotida belgilangan tartib va qoidalarga muvofiq taʻlim olish hamda oʻz bilim darajasini oshirib borish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.7. Atrofdagi shaхslar tomonidan korrupsiyaga oid huquqbuzarlikni sodir etish yoki unda qatnashishga tayyorgarlik ko‘rish sifatida baholanishi mumkin bo‘lgan хulq-atvordan o‘zini tiyish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.8. Oʻzining xatti-harakatlari, OAV va ijtimoiy tarmoqlardagi chiqishlari bilan universitetning ishchanlik obroʻsi va manfaatlariga putur yetkazmaslik.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.9. Ta’lim tashkiloti topshiriqlarini o‘z vaqtida sifatli va aniq bajarish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.10. Ta’lim tashkilotining mol-mulkiga nisbatan ehtiyotkorona munosabatda bo‘lish va undan samarali foydalanish, yеtkazilgan zararni O‘zbеkiston Rеspublikasi qonunchiligiga muvofiq qoplash.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.11. Pasport ma’lumotlari, mobil va aloqa telefonlari o’zgargan vaqtdan e’tiboran besh kun ichida dekanatga xabar berish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.12. O’qish jarayonida o’zlashtira olmaslik holatlarida Ta’lim tashkiloti tariflari bo’yicha qo’shimcha xizmatlardan foydalanganlik uchun to’lovlarni amalga oshirish;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.13. O‘qish davrida Ta’lim tashkiloti ning ruхsatisiz O‘zbеkiston Rеspublikasi hududidan tashqariga chiqmaslik.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.14. Ta’lim tashkilotiga topshirilgan hujjatlar, shu jumladan umumiy o’rta va o’rta maxsus ta’lim to’g’risidagi hujjat (shahodatnoma, diplom ilovasi bilan) haqiqiy ekanligi uchun shaxsan javobgar, hujjat asl nusxasini o’z vaqtida topshirmasligi, uning haqiqiy emasligi aniqlanishi talabani Ta’lim tashkiloti safidan chetlatishga asos bo’ladi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>III. TO’LOV MIQDORLARI VA MUDDATLARI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            3.1. 2024/2025-oʻquv yilda ta’lim olish uchun Talaba tomonidan toʻlanishi lozim boʻlgan toʻlov summasi <?= number_format((int)$contract->contract_price, 0, '', ' ') . ' (' . Contract::numUzStr($contract->contract_price) . ')'?> soʻmni tashkil etadi va quyidagi tartibda toʻlanadi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            3.2. Talaba kuzgi semestr uchun to‘lov kontrakt summasining 50% ni kuzgi semestr yakuniy nazorat imtihonlari boshlangunga qadar to‘laydi:
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            3.3. Talaba bahorgi semestr uchun to‘lov kontrakt summasining qolgan 50% ni bahorgi semestr yakuniy nazorat imtihonlari boshlangunga qadar to‘laydi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            3.4. Talaba tomonidan mazkur shartnomaning 3.1-bandida nazarda tutilgan kontrakt to‘lovining <b>100% miqdori tegishli o‘quv yilining 10-sentabrga qadar (shu kuni ham) to‘langan taqdirda to‘lov kontrakt summasining 15% miqdorida, 10-oktabrga qadar to‘langan taqdirda esa to‘lov kontrakt summasining 10% miqdorida talabaga chegirma taqdim etiladi.</b>
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            3.5. Talaba tomonidan Shartnoma boʻyicha oʻqitish qiymatini toʻlashda toʻlov topshiriqnomasida Talabaning familiyasi, ismi, sharifi hamda oʻqiyotgan kursi toʻliq koʻrsatiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.6. Quyidagi hollarda Talaba o‘qishdan chеtlashtirilganda, uning tomonidan amalga oshirilgan o‘qish uchun to‘lov qaytarib bеrilmaydi:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            a)	o‘quv intizomini va Ta’lim tashkilotining ichki tartib qoidalarini buzganligi;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            b)	bir sеmеstr davomida darslarni uzrli sabablarsiz 74 soatdan ortiq qoldirganligi;
        </td>
    </tr>


    <tr>
        <td colspan="4">
            c)	o‘qitish uchun bеlgilangan miqdordagi to‘lovni o‘z vaqtida amalga oshirmaganligi;
        </td>
    </tr>


    <tr>
        <td colspan="4">
            d)	bеlgilangan muddatlarda fanlarni o‘zlashtira olmaganligi;
        </td>
    </tr>


    <tr>
        <td colspan="4">
            e)	sud hujjatiga muvofiq javobgarlikka tortilgan bo‘lsa.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.7. Ta’lim tashkiloti tomonidan beriladigan imtiyozlarga asos bo‘luvchi hujjatlar kuzgi semestrning dastlabki 10 kunligida universitetga taqdim qilingandan so‘ng amal qiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.8. Talaba tomonidan to‘lov amalga oshirilgandan so‘ng o‘qiy olmasligini yoki o‘qishni davom ettira olmasligini bildirib murojaat qilgan taqdirda, universitetning talabaga ta’lim berish bilan bog‘liq barcha xarajatlari to‘lov summasidan ushlab qolinadi, biroq ushlab qolingan summa to‘lov summasining 20 foizidan kam bo‘lmasligi kerak.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            3.9. O‘qishini ko‘chirish istagida hujjat topshirgan talabaning ko‘chirish masalasi ijobiy hal etilgan taqdirda, OTM rektorining buyrug‘i bilan talaba darslarga qo‘yiladi hamda ushba talaba ilgari ta’lim olgan OTMga uning shaxsiy yig‘ma jildini jo‘natish uchun so‘rovnoma (aloqa xati) yuboriladi. To‘lov-shartnoma asosida ta’lim oladigan talaba to‘lovni o‘z vaqtida amalga oshirganidan keyin talabalar safiga qabul qilish to‘g‘risidagi buyruq chiqariladi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            O‘qishini ko‘chirish istagida hujjat topshirgan talabaning shaxsiy yig‘ma jildi (akademik ma’lumotnoma) belgilangan muddatlarda taqdim etilmaganda yoxud aloqa xati bo‘yicha taqdim etilgan hujjatlarning (asosiy hujjat tanskript) soxtaligi aniqlanganda talaba amalga oshirgan to‘lov qaytarilmaydi. Universitet abituriyentni uning xohishiga ko‘ra birinchi kursdan o‘qishga qabul qilishi mumkin.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            Talaba Ta’lim tashkilotining Ichki tartib-qoidalari bilan tanishtirildi.
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>IV. SHARTNOMANI BEKOR QILISH VA O’ZGARTIRISH</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            4.1. Shartnoma quyidagi hollarda bekor qilinadi:
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            4.1.1. Tomonlarning o‘zaro roziligi bilan.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.1.2. Ta’lim tashkilotining tashabbusiga ko‘ra Ustavi va boshqa ichki hujjatlariga muvofiq Talaba talabalar safidan chiqarilganda.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.1.3. O‘qitish qiymati belgilangan muddat ichida to‘lanmasa (bunda, Ta’lim tashkiloti Shartnomani bir tomonlama bekor qiladi, Talaba Talabalar safidan chiqariladi).
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.1.4. Talabaning tashabbusiga ko‘ra (yozma murojatiga asosan).
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.1.5. Shartnomaning 2.1.3-bandida ko‘rsatilgan hollarda (Ta’lim tashkiloti tomonidan Shartnomaning bir tomonlama bekor qilinishi va talabalar safidan chiqarilishi haqida Talabaga yozma xabarnoma yuborish orqali).
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.1.6. Qonunchilikda ko‘rsatilgan boshqa hollarda.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.2. Shartnoma Tomonlarning oʻzaro roziligi bilan oʻzgartiriladi,  2.1.5, 2.1.6-bandlarda koʻrsatilgan holatlar bundan mustasno.
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>V. FORS-MAJOR HOLATLARI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            5.1. Ushbu Shartnomaga asosan majburiyatlarni bajarilmasligi holatlari yengib bo‘lmas kuchlar (fors-major) holatlar natijasida vujudga kelganda Tomonlar o‘z majburiyatlarini bajarishdan qisman yoki to‘liq ozod bo‘ladilar.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            5.2. Yengib bo‘lmaydigan kuchlar (fors-major) holatlariga Tomonlarning irodasi va faoliyatiga bog‘liq bo‘lmagan tabiat hodisalari (pandemiya, zilzila, ko‘chki, bo‘ron, qurg‘oqchilik va boshqalar) yoki ijtimoiy-iqtisodiy holatlar (urush holati, qamal, davlat manfatlarini ko‘zlab import va eksportni ta‘qiqlash va boshqalar) sababli yuzaga kelgan sharoitlarda Tomonlarga qabul qilingan majburiyatlarni bajarish imkonini bermaydigan favqulotda, oldini olib bo‘lmaydigan va kutilmagan holatlar kiradi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            5.3. Shartnoma, Tomonlardan qaysi biri uchun majburiyatlarni yengib bo‘lmaydigan kuchlar (fors-major) holatlari sababli bajarmaslik ma‘lum bo‘lsa, darhol ikkinchi tomonga bu xaqda 10 kun ichida dalillar bilan taqdim etishi shart.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            5.4. Fors-major xolatlarning mavjudligi va ularning amal qilish muddati Oʼzbekiston Respublikasi davlat vakolatli organi tomonidan berilgan hujjat bilan tasdiqlanadi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>VI. YAKUNIY QOIDALAR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            6.1. Shartnoma bevosita elektron shaklda rasmiylashtirilgandan keyin Tomonlar tarafidan imzolangan hisoblanadi va kuchga kiradi hamda ushbu shartnoma bo‘yicha majburiyatlar to’liq bajarilguniga qadar amal qiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.2. Talabaning talabalar safidan chetlashtirilishi To’lovchi/Talabani Ta’lim tashkiloti tomonidan taqdim etilgan ta’lim xizmatlari uchun to’lovni amalga oshirish majburiyatidan ozod etmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.3. Talabaning akademik qarzdorligi bo’lgan taqdirda, qayta o’qitiladigan fanlar va kreditlarning sonidan kelib chiqqan holda, Kontrakt to’lovining tegishli qismi To’lovchi/Talaba tomonidan o’rnatilgan tartibda Ta’lim tashkilotiga to’lanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.4. Mehnatga haq toʻlashning eng kam miqdori oʻzgarganda mos ravishda toʻlov-kontrakt qiymati miqdori navbatdagi semestr boshidan oshiriladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.5. Shartnoma boʻyicha oʻz majburiyatlarini bajarmagan Tomon qonunchilikda belgilangan tartibda javobgarlikka tortiladi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            6.6. Tomonlar oʻrtasida Shartnoma boʻyicha vujudga kelgan nizolarni muzokaralar va talabnoma yuborish yoʻli bilan hal etadilar. Agar tomonlar nizoni oʻzaro muzokaralar va talabnoma yuborish yoʻli bilan hal eta olmasalar, mazkur nizo sudlovlilik va sudga taalluqlilik qoidalariga muvofiq fuqarolik sudida hal etiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <div>
                <table width="100%">

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="4" style="text-align: center;">
                            <b>TOMONLARNING MANZILLARI VA TOʻLOV REKVIZITLARI</b>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <b>To’lov oluvchi:</b>
                        </td>
                        <td colspan="2">
                            <b>Talaba</b>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="vertical-align: top">
                            <b><?= $filial->name_uz ?></b> <br>
                            <b>Manzil:</b> <?= $filial->address_uz ?> <br>
                            <b>H/R:</b> <?= $cons->hr ?> <br>
                            <b>Bank:</b> <?= $cons->bank_name_uz ?>  <br>
                            <b>Bank kodi (MFO):</b> <?= $cons->mfo ?>  <br>
<!--                            <b>IFUT (OKED):</b> --><?php //= $cons-> ?><!--  <br>-->
                            <b>STIR (INN):</b> <?= $cons->inn ?> <br>
                            <b>Tel:</b> <?= $cons->pochta_phone ?> <br>
                            <b>Rеktor:</b> ______________ <?= $filial->prorektor_uz ?> <br>
                        </td>
                        <td colspan="2" style="vertical-align: top">
                            <b>F.I.Sh.:</b> <?= $full_name ?> <br>
                            <b>Pasport ma’lumotlari:</b> <?= $student->passport_serial.' '.$student->passport_number ?> <br>
                            <b>JShShIR:</b> <?= $student->passport_pin ?> <br>
                            <b>Tеlefon raqami: </b> <?= $student->user->username ?> <br>
                            <b>Talaba imzosi: </b> ______________ <br>
                        </td>
                    </tr>

                </table>
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <div>
                <table width="100%">

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="text-align: center">
                            <b>“To’lovchi”</b> <br>
                            <span style="font-style: italic">(agar “To’lovchi” yuridik shaхs bo‘lsa) </span>
                        </td>
                        <td colspan="2">
                            <b>“To’lovchi”</b> <br>
                            <span style="font-style: italic">(agar “To’lovchi” jismoniy shaхs bo‘lsa) </span>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="vertical-align: top">
                            <b>Korxona nomi: </b> _______________________________________ <br>
                            _______________________________________________________ <br>
                            <b>Pochta manzili:</b> _____________________________________ <br>
                            ________________________________________________________ <br>
                            <b>H/R:</b> _________________________________________________ <br>
                            <b>Bank:</b> _________________________________________________  <br>
                            <b>Bank kodi (MFO):</b> ____________________________________  <br>
                            <b>STIR (INN):</b> __________________________________________ <br>
                            <b>Tel:</b> ___________________________________________________ <br>
                            <b>Rahbar:</b> _______________________________________________ <br>
                        </td>
                        <td colspan="2" style="vertical-align: top">
                            <b>Pasport sеriyasi va raqami/ID karta raqami:</b>  <br>
                            ___________________________________________________________ <br>
                            ___________________________________________________________ <br>
                            <b>JShShIR:</b> ____________________________________________ <br>
                            <b>Doimiy yashash joyi: </b> ____________________________________ <br>
                            ___________________________________________________________ <br>
                            <b>Talaba imzosi: </b> _____________________________________________ <br>
                            <span style="font-style: italic; text-align: center;">
                                “Shartnomaning mazmuni bilan to‘liq tanishib chiqdim va uning barcha bandlarini e’tirof etgan holda tuzishga roziman”
                            </span>
                            ____________________________________________________________ <br>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="4" style="text-align: center">
                            <b>Yurist: _______________________ M.U.Sherkuziyev</b>
                        </td>
                    </tr>

                </table>
            </div>
        </td>
    </tr>

</table>