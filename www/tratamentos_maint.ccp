<Page id="1" templateExtension="html" relativePath="." fullRelativePath="." secured="False" urlType="Relative" isIncluded="False" SSLAccess="False" isService="False" cachingEnabled="False" cachingDuration="1 minutes" wizardTheme="Simple" wizardThemeVersion="3.0" needGeneration="0">
	<Components>
		<Record id="2" sourceType="Table" urlType="Relative" secured="False" allowInsert="True" allowUpdate="True" allowDelete="True" validateData="True" preserveParameters="GET" returnValueType="Number" returnValueTypeForDelete="Number" returnValueTypeForInsert="Number" returnValueTypeForUpdate="Number" connection="Connection1" name="tratamentos" dataSource="tratamentos" errorSummator="Error" wizardCaption="Add/Edit Tratamentos " wizardFormMethod="post" returnPage="tratamentos_list.ccp" PathID="tratamentos" pasteActions="pasteActions">
			<Components>
				<Button id="3" urlType="Relative" enableValidation="True" isDefault="False" name="Button_Insert" operation="Insert" wizardCaption="Add" wizardThemeItem="FooterIMG" wizardButtonImage="ButtonInsertOn" PathID="tratamentosButton_Insert">
					<Components/>
					<Events/>
					<Attributes/>
					<Features/>
				</Button>
				<Button id="4" urlType="Relative" enableValidation="True" isDefault="False" name="Button_Update" operation="Update" wizardCaption="Submit" wizardThemeItem="FooterIMG" wizardButtonImage="ButtonUpdateOn" PathID="tratamentosButton_Update">
					<Components/>
					<Events/>
					<Attributes/>
					<Features/>
				</Button>
				<Button id="5" urlType="Relative" enableValidation="False" isDefault="False" name="Button_Delete" operation="Delete" wizardCaption="Delete" wizardThemeItem="FooterIMG" wizardButtonImage="ButtonDeleteOn" PathID="tratamentosButton_Delete">
					<Components/>
					<Events/>
					<Attributes/>
					<Features/>
				</Button>
				<ListBox id="7" visible="Yes" fieldSourceType="DBColumn" sourceType="Table" dataType="Integer" returnValueType="Number" name="Paciente_id" fieldSource="Paciente_id" required="True" caption="Paciente Id" wizardCaption="Paciente Id" wizardSize="10" wizardMaxLength="10" wizardIsPassword="False" wizardEmptyCaption="Select Value" connection="Connection1" dataSource="pacientes" boundColumn="Id" textColumn="Nome" PathID="tratamentosPaciente_id">
					<Components/>
					<Events/>
					<TableParameters/>
					<SPParameters/>
					<SQLParameters/>
					<JoinTables/>
					<JoinLinks/>
					<Fields/>
					<Attributes/>
					<Features/>
				</ListBox>
				<TextBox id="8" visible="Yes" fieldSourceType="DBColumn" dataType="Date" name="Data" fieldSource="Data" required="True" caption="Data" wizardCaption="Data" wizardSize="10" wizardMaxLength="100" wizardIsPassword="False" PathID="tratamentosData" format="dd-mm-yyyy" DBFormat="yyyy-mm-dd">
					<Components/>
					<Events/>
					<Attributes/>
					<Features/>
				</TextBox>
				<DatePicker id="9" name="DatePicker_Data" control="Data" wizardSatellite="True" wizardControl="Data" wizardDatePickerType="Image" wizardPicture="Styles/Simple/Images/DatePicker.gif" style="Styles/Simple/Style.css" PathID="tratamentosDatePicker_Data">
					<Components/>
					<Events/>
					<Attributes/>
					<Features/>
				</DatePicker>
				<TextBox id="10" visible="Yes" fieldSourceType="DBColumn" dataType="Single" name="Valor" fieldSource="Valor" required="True" caption="Valor" wizardCaption="Valor" wizardSize="12" wizardMaxLength="12" wizardIsPassword="False" PathID="tratamentosValor">
					<Components/>
					<Events/>
					<Attributes/>
					<Features/>
				</TextBox>
				<CheckBox id="11" visible="Yes" fieldSourceType="DBColumn" dataType="Integer" name="Factura" fieldSource="Factura" required="True" caption="Factura" wizardCaption="Factura" wizardSize="4" wizardMaxLength="4" wizardIsPassword="False" checkedValue="1" uncheckedValue="0" PathID="tratamentosFactura">
					<Components/>
					<Events/>
					<Attributes/>
					<Features/>
				</CheckBox>
				<TextArea id="14" visible="Yes" fieldSourceType="DBColumn" dataType="Text" name="Descricao" fieldSource="Descricao" required="True" caption="{res:Text1}" wizardCaption="Valor" wizardSize="12" wizardMaxLength="12" wizardIsPassword="False" PathID="tratamentosDescricao">
<Components/>
					<Events/>
					<Attributes/>
					<Features/>
				</TextArea>
</Components>
			<Events/>
			<TableParameters>
				<TableParameter id="6" conditionType="Parameter" useIsNull="False" field="Id" parameterSource="Id" dataType="Integer" logicOperator="And" searchConditionType="Equal" parameterType="URL" orderNumber="1"/>
			</TableParameters>
			<SPParameters/>
			<SQLParameters/>
			<JoinTables/>
			<JoinLinks/>
			<Fields/>
			<ISPParameters/>
			<ISQLParameters/>
			<IFormElements/>
			<USPParameters/>
			<USQLParameters/>
			<UConditions/>
			<UFormElements/>
			<DSPParameters/>
			<DSQLParameters/>
			<DConditions/>
			<SecurityGroups/>
			<Attributes/>
			<Features/>
		</Record>
		<IncludePage id="12" name="Header" PathID="Header" page="Header.ccp">
			<Components/>
			<Events/>
			<Features/>
		</IncludePage>
		<IncludePage id="13" name="Footer" PathID="Footer" page="Footer.ccp">
			<Components/>
			<Events/>
			<Features/>
		</IncludePage>
	</Components>
	<CodeFiles>
		<CodeFile id="Code" language="PHPTemplates" name="tratamentos_maint.php" forShow="True" url="tratamentos_maint.php" comment="//" codePage="windows-1252"/>
	</CodeFiles>
	<SecurityGroups/>
	<CachingParameters/>
	<Attributes/>
	<Features/>
	<Events/>
</Page>
