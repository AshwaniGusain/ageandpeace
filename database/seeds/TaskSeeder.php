<?php

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Category;
use App\Models\ProviderType;
use League\Csv\Reader;

class TaskSeeder extends \BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(\App\Models\Task::class, 125)->create();

        $csv = Reader::createFromPath(__DIR__.'/imports/Categories, Providers & Tasks - Tasks.csv', 'r');

        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        foreach ($records as $data) {
            if (empty($data['Task Title'])) {
                continue;
            }
            $this->createTask($data);
        }
    }

    protected function createTask($data)
    {
        $category = $this->getCategory($data['Category']);
        $providerType = $this->getProviderType($data['Provider Type']);
        //$faker = \Faker\Factory::create();
        $task = new Task(
            [
                'title' => $data['Task Title'],
                'description' => $data['Task Description'] ? : '',
                'category_id' => $category->id ?? null,
                'provider_type_id' => $providerType->id ?? null,
            ]
        );

        if (!$task->save()) {
            exit('Error saving task');
        }

        return $task;
    }

    protected function getCategory($name)
    {
        $name = ltrim($name, '- ');
        return Category::where('name', $name)->first();
    }

    protected function getProviderType($name)
    {
        $name = explode(' - ', $name);
        return ProviderType::where('name', $name[0])->first();
    }

    /* protected function getTasks()
     {
         $tasks = [
             'Medical' => [
                     'List all of your health conditions:
                         a.	Guide to conversation with Doctor,
                         b. Create a list of all doctors and care providers,
                         c.	List of procedures and surgeries completed',
                     'Create a list of all prescribed and over the counter medications and any supplements including the amount taken',
                     'Create an emergency plan in case of an evacuation for medications and other needs',
                     'Create a plan for medication refill or delivery for current prescriptions',
                     'Create an Advanced Healthcare directive',
                     'Complete Hippa release form for care taker',
                     'Complete a Do Not Resuscitate/Do not Intubate Order if that is your wishes',
                     'Identify doctors for common health needs (two doctors per category)',
                 ],
             'Mental / Dementia' => [
                     'Educate yourself/family on identifiers of early Dementia',
                     'Identify a guardian to help with decision making process',
                     'Create a daily living plan',
                     'Have a home safety check',
                     'Look into tacking technologies',
                     'Review your medications to see if they impact memory',
                     'Create a plan to support yourself or loved one with dementia',
             ],
             'Wellness' => [
                     'Build a diet optimized for you lifestyle',
                     'Have you built a personalized activity plan',
                     'Complete a sleep assessment',
                     'Create a personal care plan (Hair, Nails, Skin, etc)',
             ],
             'Financial' => [
                     'Identify sources of income and expenses/dept and create budget',
                     'List your accounts and any safety deposit boxes (investments, banks, etc)',
                     'Identify tangible and intangible (Define) property owned - make sure it’s part of estate planning',
                     'Keep your last tax return in accessible location',
                     'Identify a trusted individual to be a co-signer for your accounts',
                     'Ensure care team are able to perform and support effectively',
                     'Create a plan for the needs of a disabled child',
                     'Identify a tax specialist who specialized in seniors',
                     'Monitor your accounts for credit history and fraud',
                     'Identify and review commonly used resources to help with senior living costs',
             ],
             'Insurance' => [
                     'Review various Insurance policies that are important to your financial plan',
                     'List your current policies and coverage details',
             ],
             'Benefit Programs' => [ // Labeled Benefits in Word Doc
                     'Review health care policies to ensure coverage and minimize costs',
                     'Complete a Medicare Detailed Plan with a trusted advisor',
                     'For more personal care look into primary care physician’s concierge/retainer services',
                     'If you are a veteran or caring for a veteran look into Veteran benefits (Jamie to expand)',
                     'Gov Benefits – Fed/State/City – employment, advocate groups',
             ],
             'Leaving a Legacy' => [ // Labeled Legacy in Word Doc
                     'Create a social will for your social media, email and websites',
                     'Create a plan for your documents, cloud storage accounts, and images',
                     'Develop remembrance activities – plant tree, stiff drink, climb mt, etc',
                     'Schedule to have your oral history recorded and stored in the national library for your families to hear for generations to come',
                     'Complete a DNA ancestry test',
                     'Communicate your plan for charities or giving, add to your will',
             ],
             'End of Life' => [ // Labeled End of Life Services in Word Doc
                     'Prepay for your funeral service',
                     'Look into funeral insurance',
                     'Communicate your body disposition – Burial, Cremation, Body Donation',
                     'Plan for the important pieces of your end of life celebration',
             ],
             'Legal' => [
                     'Create a durable power of attorney (DPOA) – Acts on behalf of elder',
                     'Create an Advance Directive–',
                     'Create a living Will and trust',
                     'HIPAA Docs',
                     'Create a Health Care Power of Attorney / Health Care Proxy',
                     'Create a Do Not Resuscitate/Do not Intubate Order if that is your wishes',
                     'Create a plan for Guardianship',
                     'Store all legal documents in accessible safe location. - Birth Certificate, Marriage Certificate, Death Certificate (spouse), Divorce Papers, Military records, Driver’s license, Passport',
             ],
             'Home and Care' => [
                     'Have a professional assessment of the safety of home and plan to prepare the home to make it safe',
                     'Create a list of Home/Auto Bills/services – Utility, telephone, home owners ins, personal prop insurance, property tax , garbage service, etc - Transportation checklist – Autos, loan information, titles, insurance, parking, etc',
                     'Look into Home technology/Security system notifications and make your family/caregiver part of communication/notification plan',
                     'Place a key with a trusted neighbor who can respond during day in case of emergency',
                     'Plan to provide safety check - food, cleanliness, clothes, etc',
                     'Look into services to support seniors at home',
                     'Look into driving or ride share options',
                     'Develop with your parent a plan to evaluate his or her driving skills on a periodic basis',
             ],
             'Living Transitions' => [ // Labeled Transitions in Word Doc
                     'Find a realtor who is specialized in senior communities and services',
                     'Inventory and document your home for estate plan, will or moving security',
                     'Identified a property manager if you want to maintain ownership of your property',
                     'Identified a Moving company specializing in elder transitions',
                     'Identify bonded and insured storage facilities',
                     'Identify an estate sale for unwanted/un needed items',
                     'Identify donation groups who pick up items',
             ],
             'Care Communities' => [ // Labeled Care Services in Word Doc
                     'Identify your personal care team',
                     'Create a complete and comprehensive care plan for caregivers and treatment team',
                     'Develop a crisis plan (health, break-in, scams, etc)',
                     'Develop evaluation criteria for caregivers, home care workers',
                     'Review the different types of senior living options to create strategy for different levels of needs',
                     'Plan for the different types of In-home Needs',
                     'Determine your choices for elder living Options',
                     'Create a plan for Physical Therapy post injury or surgery',
                     'Plan for a short term/day care/in home care post procedure',
                     'Create a temporary and/or long-term care plan for animals',
                     'Create list of medical and diet needs for animals/pets',
                     'Have an address book/online list of friends and contacts',
             ],
             'Spiritual Journey' => [ // Labeled Spiritual Services in Word Doc
                     'Identify places to worship',
                     'Create plan for continued attendance of services and group meetings',
                     'Look into Spiritual Counseling',
                     'Locate Life Coach',
                     'Identify Chaplin Services',
             ],
             'Out and About' => [
                     'List of organizations they are involved in',
                     'Locate your community services for elders',
                     'Identify local Thrive Program',
                     'Identify local Art’s and Music events',
                     'Identify local Sporting events',
                     'Identify elder travel agencies and groups',
                     'Identify continued education opportunities',
                     'Join elder community groups',
             ],
             'Care Giving' => [ // Labeled Caregiving in Word Doc
                     'Find a qualified Elder Law Attorney',
                     'Understand Your Rights as a Caregiver Under the Family and Medical Leave Act of 1993',
                     'Find training opportunities to provide the needed skills to care for a loved one',
                     'Complete the 10 must-dos when serving as a caregiver for family, friends',
                     'Know the signs of abuse, neglect and exploitation',
                     'Complete a Healthcare Power of Attorney',
                     'Complete a Financial Power of Attorney',
             ],
             'Technology' => [
                     'Create a list of your (digital property) online accounts and passwords',
                     'Review the latest technology solutions for healthcare needs',
                     'Look into technology solutions for medication dispensing devices',
                     'Review online medical providers',
                     'Look into ways to use technology to leave a legacy',
                     'Take a class on how to use technology in your everyday life',
             ]
         ];

        return $tasks;
    }*/

}
