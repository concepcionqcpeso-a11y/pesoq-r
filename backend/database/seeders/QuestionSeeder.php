<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder {
    public function run(): void {
        $questions = [
            // --- BPO / Customer Service ---
            ['role_key'=>'bpo','role_label'=>'Customer Service / BPO',
             'question_en'=>'Tell me about a time you dealt with a difficult customer.',
             'question_tl'=>'Magkuwento ng pagkakataon na kailangan mong harapin ang isang mahirap na customer.',
             'tip_en'=>'Focus on staying calm, listening actively, and resolving the issue professionally.',
             'tip_tl'=>'Ipakita na kaya mong manatiling mahinahon, makinig nang mabuti, at resolbahin ang problema nang propesyonal.'],

            ['role_key'=>'bpo','role_label'=>'Customer Service / BPO',
             'question_en'=>'How do you handle multiple tasks or calls at the same time?',
             'question_tl'=>'Paano mo pinamamahalaan ang maraming gawain o tawag nang sabay-sabay?',
             'tip_en'=>'Mention prioritization, using tools/notes, and staying organized under pressure.',
             'tip_tl'=>'Banggitin ang pag-uuna ng mga gawain, paggamit ng mga tool o tala, at pananatiling organisado sa ilalim ng presyon.'],

            ['role_key'=>'bpo','role_label'=>'Customer Service / BPO',
             'question_en'=>'Why do you want to work in a BPO environment?',
             'question_tl'=>'Bakit mo gustong magtrabaho sa isang BPO na kapaligiran?',
             'tip_en'=>'Show enthusiasm for communication, helping people, and career growth opportunities.',
             'tip_tl'=>'Ipakita ang iyong pagmamahal sa komunikasyon, pagtulong sa mga tao, at oportunidad para sa pag-unlad ng karera.'],

            ['role_key'=>'bpo','role_label'=>'Customer Service / BPO',
             'question_en'=>'Describe your communication style when speaking with a frustrated customer.',
             'question_tl'=>'Ilarawan ang iyong estilo ng komunikasyon kapag nakikipag-usap sa isang naiinip na customer.',
             'tip_en'=>'Emphasize empathy, clear language, and a solution-first mindset.',
             'tip_tl'=>'Bigyang-diin ang empatiya, malinaw na wika, at isang pag-iisip na nakatuon sa solusyon.'],

            ['role_key'=>'bpo','role_label'=>'Customer Service / BPO',
             'question_en'=>'What does good customer service mean to you?',
             'question_tl'=>'Ano ang ibig sabihin para sa iyo ng magandang serbisyo sa customer?',
             'tip_en'=>'Go beyond scripted answers — share a personal belief about respect, speed, and satisfaction.',
             'tip_tl'=>'Huwag manatili sa mga script — ibahagi ang iyong personal na paniniwala tungkol sa paggalang, bilis, at kasiyahan.'],

            // --- Food & Beverage ---
            ['role_key'=>'fnb','role_label'=>'Service Crew / Food & Beverage',
             'question_en'=>'How do you handle a situation where a customer is unhappy with their order?',
             'question_tl'=>'Paano mo haharapin ang isang sitwasyon kung saan hindi masaya ang customer sa kanilang order?',
             'tip_en'=>'Show willingness to resolve quickly — offer to replace, escalate, or apologize sincerely.',
             'tip_tl'=>'Ipakita ang iyong kagustuhang resolbahin agad — mag-alok na palitan, mag-escalate, o humingi ng tawad nang taos-puso.'],

            ['role_key'=>'fnb','role_label'=>'Service Crew / Food & Beverage',
             'question_en'=>'Can you work during holidays and weekends?',
             'question_tl'=>'Kaya mo bang magtrabaho sa mga holiday at weekend?',
             'tip_en'=>'Be honest about your availability. Employers value transparency over false promises.',
             'tip_tl'=>'Maging tapat sa iyong availability. Pinahahalagahan ng mga employer ang pagiging transparent kaysa sa mga maling pangako.'],

            ['role_key'=>'fnb','role_label'=>'Service Crew / Food & Beverage',
             'question_en'=>'Describe how you stay efficient during a rush hour.',
             'question_tl'=>'Ilarawan kung paano ka nananatiling mahusay sa oras ng rush hour.',
             'tip_en'=>'Mention teamwork, staying calm, knowing the menu well, and quick hand-eye coordination.',
             'tip_tl'=>'Banggitin ang teamwork, pananatiling mahinahon, malalim na kaalaman sa menu, at mabilis na koordinasyon.'],

            ['role_key'=>'fnb','role_label'=>'Service Crew / Food & Beverage',
             'question_en'=>'How do you maintain food safety and hygiene standards?',
             'question_tl'=>'Paano mo pinapanatili ang mga pamantayan sa kaligtasan ng pagkain at kalinisan?',
             'tip_en'=>'Reference handwashing, proper storage, expiry checks, and following SOPs.',
             'tip_tl'=>'Banggitin ang paghuhugas ng kamay, wastong pag-iimbak, pagsusuri ng expiry, at pagsunod sa mga SOP.'],

            ['role_key'=>'fnb','role_label'=>'Service Crew / Food & Beverage',
             'question_en'=>'What would you do if you ran out of a menu item a customer ordered?',
             'question_tl'=>'Ano ang gagawin mo kung wala nang stock ng menu item na inorder ng customer?',
             'tip_en'=>'Offer alternatives, apologize sincerely, and inform the supervisor.',
             'tip_tl'=>'Mag-alok ng mga alternatibo, humingi ng tawad nang taos-puso, at ipaalam sa superbisor.'],

            // --- Admin & Office ---
            ['role_key'=>'admin','role_label'=>'Administrative & Office Staff',
             'question_en'=>'How do you prioritize tasks when you have multiple deadlines?',
             'question_tl'=>'Paano mo pinipili ang mga gawain kapag mayroon kang maraming deadline?',
             'tip_en'=>'Mention using to-do lists, calendars, and communicating with your team on priorities.',
             'tip_tl'=>'Banggitin ang paggamit ng mga to-do list, kalendaryo, at pakikipag-ugnayan sa iyong team tungkol sa mga priyoridad.'],

            ['role_key'=>'admin','role_label'=>'Administrative & Office Staff',
             'question_en'=>'What office software or tools are you comfortable using?',
             'question_tl'=>'Anong mga software o tool sa opisina ang komportable mong gamitin?',
             'tip_en'=>'Name specific tools: MS Office, Google Workspace, email clients, or any ERP you know.',
             'tip_tl'=>'Pangalanan ang mga partikular na tool: MS Office, Google Workspace, email clients, o anumang ERP na alam mo.'],

            ['role_key'=>'admin','role_label'=>'Administrative & Office Staff',
             'question_en'=>'Describe a time you caught an error in a document or report.',
             'question_tl'=>'Ilarawan ang isang pagkakataon na natuklasan mo ang isang pagkakamali sa isang dokumento o ulat.',
             'tip_en'=>'Show attention to detail and the steps you took to correct and prevent recurrence.',
             'tip_tl'=>'Ipakita ang iyong pagiging maingat at ang mga hakbang na ginawa mo para itama at maiwasang maulit ito.'],

            ['role_key'=>'admin','role_label'=>'Administrative & Office Staff',
             'question_en'=>'How do you handle confidential information?',
             'question_tl'=>'Paano mo pinamamahalaan ang mga kumpidensyal na impormasyon?',
             'tip_en'=>'Emphasize discretion, secure storage, and only sharing on a need-to-know basis.',
             'tip_tl'=>'Bigyang-diin ang pagiging maingat, ligtas na pag-iimbak, at pagbabahagi lamang sa mga may kailangan nito.'],

            ['role_key'=>'admin','role_label'=>'Administrative & Office Staff',
             'question_en'=>'Tell us about your experience with scheduling and calendar management.',
             'question_tl'=>'Sabihin sa amin ang tungkol sa iyong karanasan sa pag-schedule at pamamahala ng kalendaryo.',
             'tip_en'=>'Give a concrete example with tools used, and how you avoided conflicts or overlaps.',
             'tip_tl'=>'Magbigay ng kongkretong halimbawa kasama ang mga ginamit na tool, at kung paano mo naiwasan ang mga salungatan o pagkakapatong.'],

            // --- Logistics & Skilled Trades ---
            ['role_key'=>'logistics','role_label'=>'Logistics & Skilled Trades',
             'question_en'=>'How do you ensure accuracy when picking, packing, or delivering items?',
             'question_tl'=>'Paano mo tinitiyak ang katumpakan kapag pumipili, nagpapadala, o nagde-deliver ng mga aytem?',
             'tip_en'=>'Mention checklists, barcode scanning, double-checking, and reporting discrepancies.',
             'tip_tl'=>'Banggitin ang mga checklist, barcode scanning, double-checking, at pag-uulat ng mga pagkakaiba.'],

            ['role_key'=>'logistics','role_label'=>'Logistics & Skilled Trades',
             'question_en'=>'Describe how you handle heavy workloads or physically demanding tasks.',
             'question_tl'=>'Ilarawan kung paano mo hinaharap ang mabibigat na trabaho o mga gawaing pisikal na naghahanap ng lakas.',
             'tip_en'=>'Show endurance, proper lifting technique awareness, and knowing your limits for safety.',
             'tip_tl'=>'Ipakita ang iyong tibay, kaalaman sa wastong paraan ng pagbubuhat, at pag-alam sa iyong mga limitasyon para sa kaligtasan.'],

            ['role_key'=>'logistics','role_label'=>'Logistics & Skilled Trades',
             'question_en'=>'What safety practices do you follow in a warehouse or job site?',
             'question_tl'=>'Anong mga kasanayan sa kaligtasan ang iyong sinusunod sa isang bodega o lugar ng trabaho?',
             'tip_en'=>'Reference PPE use, hazard identification, proper signage, and following safety SOPs.',
             'tip_tl'=>'Banggitin ang paggamit ng PPE, pagtukoy ng mga panganib, wastong signage, at pagsunod sa mga SOP sa kaligtasan.'],

            ['role_key'=>'logistics','role_label'=>'Logistics & Skilled Trades',
             'question_en'=>'How do you stay organized when managing multiple deliveries or tasks?',
             'question_tl'=>'Paano ka nananatiling organisado kapag namamahala ng maraming delivery o gawain?',
             'tip_en'=>'Mention route planning, manifest checklists, and communicating ETAs proactively.',
             'tip_tl'=>'Banggitin ang pagpaplano ng ruta, mga checklist ng manifest, at proaktibong pakikipag-ugnayan tungkol sa ETA.'],

            ['role_key'=>'logistics','role_label'=>'Logistics & Skilled Trades',
             'question_en'=>'What do you do when equipment or tools malfunction during work?',
             'question_tl'=>'Ano ang gagawin mo kapag may sira ang kagamitan o mga kasangkapan sa trabaho?',
             'tip_en'=>'Show problem-solving: stop, report to supervisor, document it, and find an interim solution.',
             'tip_tl'=>'Ipakita ang paglutas ng problema: huminto, iulat sa superbisor, idokumento ito, at maghanap ng pansamantalang solusyon.'],
        ];

        DB::table('questions')->insert($questions);
    }
}